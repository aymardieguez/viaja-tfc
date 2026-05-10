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

        $urlsImagenes = [];
        try {
            $unsplashResponse = Http::get('https://api.unsplash.com/search/photos', [
                'client_id' => env('UNSPLASH_ACCESS_KEY'),
                'query' => $validated['destino'],
                'per_page' => 3,
                'orientation' => 'landscape'
            ]);

            if ($unsplashResponse->successful()) {
                $resultados = $unsplashResponse->json()['results'];
                foreach ($resultados as $foto) {
                    $urlsImagenes[] = $foto['urls']['regular'];
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Fallo en la API de Unsplash: ' . $e->getMessage());
        }

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

        $motorIA = (isset($validated['modo_pro']) && $validated['modo_pro'])
            ? 'gemini-3-flash-preview'
            : 'gemini-2.5-flash';

        $prompt = "Actúa como un guía turístico local EXPERTO y ESTRICTAMENTE PRECISO de {$validated['destino']}.
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
            $respuestaGemini = Gemini::generativeModel($motorIA)
                ->withGenerationConfig(
                    new GenerationConfig(
                        responseMimeType: ResponseMimeType::APPLICATION_JSON,
                    )
                )
                ->generateContent($prompt);

            $textoRaw = $respuestaGemini->text();
            $textoLimpiado = str_replace(['```json', '```'], '', $textoRaw);
            $inicio = strpos($textoLimpiado, '[');
            preg_match('/\}\s*\]/', $textoLimpiado, $coincidencias, PREG_OFFSET_CAPTURE);

            if ($inicio !== false && !empty($coincidencias)) {
                $fin = $coincidencias[0][1] + strlen($coincidencias[0][0]) - 1;
                $textoLimpiado = substr($textoLimpiado, $inicio, $fin - $inicio + 1);
            } else {
                $fin = strrpos($textoLimpiado, ']');
                if ($inicio !== false && $fin !== false) {
                    $textoLimpiado = substr($textoLimpiado, $inicio, $fin - $inicio + 1);
                }
            }

            $diasGenerados = json_decode($textoLimpiado, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                dd([
                    'Error JSON' => json_last_error_msg(),
                    'Lo que envió la IA' => $textoRaw,
                    'Lo que recortó PHP' => $textoLimpiado
                ]);
            }

            if (is_array($diasGenerados)) {
                foreach ($diasGenerados as $dia) {
                    $viaje->dias()->create([
                        'numero_dia' => $dia['numero_dia'],
                        'titulo' => $dia['titulo'],
                        'descripcion' => $dia['descripcion'],
                    ]);
                }
            }
        } catch (\Exception $e) {
            dd("Error de conexión con la API: " . $e->getMessage());
        }

        return redirect()->route('viajes.show', $viaje->id);
    }

    public function show($id)
    {
        $viaje = Auth::user()->viajes()->with('dias')->findOrFail($id);

        return Inertia::render('Viajes/Show', [
            'viaje' => $viaje
        ]);
    }
}
