<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Auth;

class ViajeController extends Controller
{
    public function index()
    {
        $viajes = Auth::user()->viajes;
        return Inertia::render('Viajes/Index', [
            'viajes' => $viajes
        ]);
    }

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
            'filtros' => 'nullable|array',
            'personas' => 'required|integer|min:1|max:20',
            'intereses' => 'nullable|array',
        ]);

        $viaje = Auth::user()->viajes()->create([
            'titulo' => 'Viaje a ' . $validated['destino'],
            'destino' => $validated['destino'],
            'presupuesto' => $validated['presupuesto'],
            'noches' => $validated['noches'],
            'filtros_ia' => $validated['filtros'] ?? null,
            'personas' => $validated['personas'],
            'intereses' => $validated['intereses'] ?? null,
        ]);

        $prompt = "Eres un planificador de viajes experto y un guía turístico local especializado en {$validated['destino']}.
        Tu objetivo es crear un itinerario REALISTA, EXHAUSTIVO y MUY ESPECÍFICO de {$validated['noches']} noches para {$validated['personas']} personas.
        Presupuesto total del usuario: {$validated['presupuesto']} euros.

        REGLAS ESTRICTAS QUE DEBES CUMPLIR SÍ O SÍ:
        1. GEOGRAFÍA EXACTA: Todas las recomendaciones (restaurantes, hoteles, actividades) deben estar EXCLUSIVAMENTE en el municipio de {$validated['destino']} o a un máximo de 15 minutos en coche. PROHIBIDO recomendar lugares de otros pueblos lejanos.
        2. NOMBRES REALES: Debes proporcionar nombres reales y verificables de restaurantes, hoteles, museos y calles. No uses descripciones genéricas como 'un restaurante local'.
        3. ESTRUCTURA OBLIGATORIA: En el campo 'descripcion', debes dividir cada día estrictamente en estas 4 secciones usando iconos:
           ☀️ MAÑANA:
           🌇 TARDE:
           🌙 NOCHE:
           🏨 ALOJAMIENTO SUGERIDO: \n";

        if (!empty($validated['filtros'])) {
            $filtrosTexto = json_encode($validated['filtros']);
            $prompt .= "\n4. NECESIDADES ESPECIALES (CRÍTICO): El usuario requiere estrictamente: {$filtrosTexto}. 
            Es VITAL y OBLIGATORIO que los nombres de los hoteles y restaurantes que propongas estén adaptados a estas necesidades (por ejemplo, si usa silla de ruedas, nombra lugares que sepas que no tienen barreras arquitectónicas).\n";
        }

        if (!empty($validated['intereses'])) {
            $interesesTexto = implode(", ", $validated['intereses']);
            $prompt .= " ENFOQUE DEL VIAJE: Prioriza actividades relacionadas con: {$interesesTexto}. ";
        }

        // El formato de salida intacto
        $prompt .= "\nIMPORTANTE: Devuelve tu respuesta EXCLUSIVAMENTE como un array JSON válido, sin texto adicional y sin formato markdown. La estructura de cada objeto debe ser:
        [
            {
                \"numero_dia\": 1,
                \"titulo\": \"Título atractivo del día\",
                \"descripcion\": \"☀️ MAÑANA: [Texto...]\\n\\n🌇 TARDE: [Texto...]\\n\\n🌙 NOCHE: [Texto...]\\n\\n🏨 ALOJAMIENTO SUGERIDO: [Nombre del hotel]\"
            }
        ]";

        try {
            $respuestaGemini = Gemini::generativeModel('gemini-2.5-flash')->generateContent($prompt);
            $textoRaw = $respuestaGemini->text();
            $textoLimpiado = preg_replace('/^```json|```$/m', '', $textoRaw);
            $textoLimpiado = trim($textoLimpiado);
            $diasGenerados = json_decode($textoLimpiado, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                dd([
                    'Error JSON' => json_last_error_msg(),
                    'Lo que envió la IA' => $textoRaw,
                    'Lo que intentamos limpiar' => $textoLimpiado
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
