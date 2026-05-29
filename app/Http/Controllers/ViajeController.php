<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Gemini\Laravel\Facades\Gemini;
use Gemini\Data\GenerationConfig;
use Gemini\Enums\ResponseMimeType;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ViajeController extends Controller
{
    public function create()
    {
        return Inertia::render("Viajes/Create");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'destino' => 'required|string|max:255',
            'presupuesto' => 'required|numeric|min:0',
            'noches' => 'required|integer|min:1|max:15',
            'personas' => 'required|integer|min:1|max:20',
            'mes' => 'required|string',
            'rango_edad' => 'required|string',
            'modo_pro' => 'boolean',
            'intereses' => 'nullable|array',
            'filtros' => 'nullable|array',
            'filtros_extra' => 'nullable|string|max:255',
        ]);

        $arrayFiltros = $validated['filtros'] ?? [];
        if (!empty($validated['filtros_extra'])) {
            $arrayFiltros[] = $validated['filtros_extra'];
        }

        $motorIA = (isset($validated['modo_pro']) && $validated['modo_pro'])
            ? 'gemini-3-flash-preview'
            : 'gemini-2.5-flash';

        $prompt = "Eres un agente de viajes estricto de la plataforma VIAJA. El usuario solicita un viaje a: '{$validated['destino']}'.

        REGLA DE SEGURIDAD CRÍTICA Y ABSOLUTA:
        Analiza literalmente la palabra '{$validated['destino']}'. ¿Es directamente el nombre de una ciudad, país, pueblo o accidente geográfico real?
        Si el usuario ha escrito el nombre de una persona (ej. Messi, Ronaldo, Batman), un objeto, una marca, o algo que NO es un destino geográfico por sí mismo, ESTÁ TOTALMENTE PROHIBIDO deducir su ciudad natal o buscar un lugar relacionado. No seas 'servicial'. Debes abortar inmediatamente y devolver ÚNICAMENTE este JSON:
        [
            {
                \"error_validacion\": \"El destino no es válido\"
            }
        ]

        Si '{$validated['destino']}' SÍ es un lugar geográfico válido, ignora la regla anterior y genera el itinerario en el siguiente formato JSON:
        
        Actúa como un guía turístico local EXPERTO y ESTRICTAMENTE PRECISO de {$validated['destino']}.
        Crea un itinerario de {$validated['noches']} noches para {$validated['personas']} personas.
        Mes: {$validated['mes']} | Presupuesto: {$validated['presupuesto']}€.

        REGLAS INQUEBRANTABLES:
        1. GEOGRAFÍA EXACTA: Verifica mentalmente la ubicación exacta de {$validated['destino']}. NO mezcles lugares de otras provincias.
        2. CERO INVENCIONES: Solo nombres de restaurantes, hoteles y lugares 100% REALES. Si no conoces locales en esa zona, escribe 'Elección libre por la zona'. PROHIBIDO inventar nombres.
        3. FORMATO DIARIO: Describe cada día usando estrictamente estas etiquetas:
        ☀️ MAÑANA:
        🌇 TARDE:
        🌙 NOCHE:\n";

        if (!empty($arrayFiltros)) {
            $filtrosTexto = implode(", ", $arrayFiltros);
            $prompt .= "\n4. NECESIDADES: El usuario requiere: {$filtrosTexto}. Si recomiendas un lugar real, asegúrate de que sea compatible.\n";
        }

        if (!empty($validated['intereses'])) {
            $interesesTexto = implode(", ", $validated['intereses']);
            $prompt .= "\n5. INTERESES: Centra las actividades en: {$interesesTexto}.\n";
        }

        if (isset($validated['modo_pro']) && $validated['modo_pro']) {
            $prompt .= "\n6. MODO PRO (ITINERARIO EXTENDIDO Y PROFUNDO): El usuario ha optado por la versión premium. Escribe párrafos LARGOS, RICOS Y MUY DETALLADOS. Por cada lugar que recomiendes, explica su historia, por qué merece la pena, qué platos específicos pedir en el restaurante recomendado y detalles logísticos reales de cómo llegar. NO seas breve.\n";
        } else {
            $prompt .= "\n6. MODO ESTÁNDAR: Sé directo y conciso con los planes.\n";
        }

        $prompt .= "\nIMPORTANTE - ESTRUCTURA JSON OBLIGATORIA:
        Devuelve SOLO un array JSON válido.
        - El Día 1 DEBE incluir obligatoriamente la etiqueta '🏨 ALOJAMIENTO:' al final.
        - Del Día 2 en adelante, NO incluyas alojamiento.
        
        EJEMPLO EXACTO DE SALIDA (Respeta el formato JSON estrictamente):
        [
            {
                \"numero_dia\": 1,
                \"titulo\": \"Título del primer día\",
                \"descripcion\": \"☀️ MAÑANA: [Texto abundante y detallado]\\n\\n🌇 TARDE: [Texto abundante y detallado]\\n\\n🌙 NOCHE: [Texto abundante y detallado]\\n\\n🏨 ALOJAMIENTO: [Nombre real o 'Buscar en la zona']\"
            },
            {
                \"numero_dia\": 2,
                \"titulo\": \"Título del segundo día\",
                \"descripcion\": \"☀️ MAÑANA: [Texto abundante y detallado]\\n\\n🌇 TARDE: [Texto abundante y detallado]\\n\\n🌙 NOCHE: [Texto abundante y detallado]\"
            }
        ]";

        try {
            $cacheIA = 'viaje_ia_' . md5(json_encode($validated));
            $textoLimpiado = Cache::remember($cacheIA, 86400, function () use ($motorIA, $prompt) {
                $respuestaGemini = Gemini::generativeModel($motorIA)
                    ->withGenerationConfig(
                        new GenerationConfig(
                            responseMimeType: ResponseMimeType::APPLICATION_JSON,
                        )
                    )
                    ->generateContent($prompt);

                $textoRaw = $respuestaGemini->text();
                $textoProcesado = str_replace(['```json', '```'], '', $textoRaw);

                $inicio = strpos($textoProcesado, '[');
                preg_match('/\}\s*\]/', $textoProcesado, $coincidencias, PREG_OFFSET_CAPTURE);

                if ($inicio !== false && !empty($coincidencias)) {
                    $fin = $coincidencias[0][1] + strlen($coincidencias[0][0]) - 1;
                    $textoProcesado = substr($textoProcesado, $inicio, $fin - $inicio + 1);
                } else {
                    $fin = strrpos($textoProcesado, ']');
                    if ($inicio !== false && $fin !== false) {
                        $textoProcesado = substr($textoProcesado, $inicio, $fin - $inicio + 1);
                    }
                }

                //si la IA devuelve un JSON roto, lanzamos excepción para que Redis no lo guarde
                json_decode($textoProcesado, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception("JSON devuelto por la IA es inválido.");
                }

                return $textoProcesado;
            });

            $diasGenerados = json_decode($textoLimpiado, true);

            $esError = false;
            if (isset($diasGenerados['error_validacion']) || isset($diasGenerados[0]['error_validacion'])) {
                $esError = true;
            }

            if ($esError) {
                return back()->withErrors([
                    'destino' => 'El destino no es válido. Por favor, introduce una ciudad, país o región real.'
                ])->withInput();
            }

            //si no hay errores cogemos las imágenes de Unsplash
            $busqueda = trim(explode(',', $validated['destino'])[0]);
            $cacheUnsplash = 'unsplash_fotos_' . Str::slug($busqueda);
            $urlsImagenes = Cache::remember($cacheUnsplash, (86400 * 7), function () use ($busqueda) {
                $urls = [];
                try {
                    $unsplashResponse = Http::get('https://api.unsplash.com/search/photos', [
                        'client_id' => env('UNSPLASH_ACCESS_KEY'),
                        'query' => $busqueda,
                        'per_page' => 3,
                        'orientation' => 'landscape'
                    ]);

                    if ($unsplashResponse->successful()) {
                        $resultados = $unsplashResponse->json()['results'];
                        foreach ($resultados as $foto) {
                            $urls[] = $foto['urls']['regular'];
                        }
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning('Fallo en la API de Unsplash: ' . $e->getMessage());
                }
                return $urls;
            });

            //creamos viaje y guardamos en base de datos
            $viaje = Auth::user()->viajes()->create([
                'titulo' => 'Viaje a ' . $validated['destino'],
                'destino' => $validated['destino'],
                'presupuesto' => $validated['presupuesto'],
                'noches' => $validated['noches'],
                'personas' => $validated['personas'],
                'mes' => $validated['mes'],
                'rango_edad' => $validated['rango_edad'],
                'modo_pro' => $validated['modo_pro'] ?? false,
                'intereses' => $validated['intereses'] ?? null,
                'filtros_ia' => empty($arrayFiltros) ? null : $arrayFiltros,
                'imagenes' => $urlsImagenes,
            ]);

            if (is_array($diasGenerados)) {
                foreach ($diasGenerados as $dia) {
                    $viaje->dias()->create([
                        'numero_dia' => $dia['numero_dia'],
                        'titulo' => $dia['titulo'],
                        'descripcion' => $dia['descripcion'],
                    ]);
                }
            }

            return redirect()->route('viajes.show', $viaje->id);
        } catch (\Exception $e) {
            // si el error contiene la palabra "demand" o "overloaded"
            if (str_contains($e->getMessage(), 'demand') || str_contains($e->getMessage(), '429')) {
                return back()->with('error', 'La IA está experimentando un pico de demanda alta. Por favor, inténtalo de nuevo en un par de minutos.');
            }

            // error diferente a demanda alta
            return back()->with('error', 'Hubo un problema de conexión con la IA. Inténtalo de nuevo.');
        }
    }

    public function show($id)
    {
        $viaje = Auth::user()->viajes()->with('dias')->findOrFail($id);

        // generamos enlace
        $shareUrl = URL::signedRoute('viajes.public', ['viaje' => $viaje->id]);

        return Inertia::render('Viajes/Show', [
            'viaje' => $viaje,
            'shareUrl' => $shareUrl
        ]);
    }

    public function enlaceCompartirViaje($id)
    {
        // el enlace es público, no hace falta estar logueado
        $viaje = \App\Models\Viaje::with('dias')->findOrFail($id);

        return Inertia::render('Viajes/Public', [
            'viaje' => $viaje
        ]);
    }

    public function destroy($id)
    {
        $viaje = \Illuminate\Support\Facades\Auth::user()->viajes()->findOrFail($id);
        $viaje->delete();
        return back();
    }

    public function descargarPdf($id)
    {
        $viaje = Auth::user()->viajes()->with('dias')->findOrFail($id);

        $imagenBase64 = null;

        if ($viaje->imagenes && count($viaje->imagenes) > 0) {
            try {
                $url = $viaje->imagenes[0];
                $contenido = file_get_contents($url);
                $tipo = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
                if (!$tipo) $tipo = 'jpeg';
                $imagenBase64 = 'data:image/' . $tipo . ';base64,' . base64_encode($contenido);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning("Error al procesar imagen para PDF: " . $e->getMessage());
            }
        }

        $pdf = Pdf::loadView('pdf.viaje', compact('viaje', 'imagenBase64'));

        $nombreArchivo = 'itinerario-' . \Illuminate\Support\Str::slug($viaje->destino) . '.pdf';
        return $pdf->download($nombreArchivo);
    }

    public function favoritos()
    {
        $viajes = Auth::user()->viajes()
            ->where('favorito', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Viajes/Favoritos', [
            'viajes' => $viajes
        ]);
    }

    public function toggleFavorito(Request $request, $id)
    {
        $viaje = Auth::user()->viajes()->findOrFail($id);
        $viaje->favorito = !$viaje->favorito;
        $viaje->save();

        return redirect()->back();
    }

    public function valorar(Request $request, $id)
    {
        $request->validate([
            'valoracion' => 'required|integer|min:1|max:5'
        ]);

        $viaje = Auth::user()->viajes()->findOrFail($id);
        $viaje->valoracion = $request->valoracion;
        $viaje->save();

        return redirect()->back();
    }
}
