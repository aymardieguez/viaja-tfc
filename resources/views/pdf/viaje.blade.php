<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Viaje a {{ $viaje->destino }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #2563EB;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .title {
            color: #1e3a8a;
            font-size: 28px;
            font-weight: bold;
            margin: 0;
        }

        .subtitle {
            color: #6b7280;
            font-size: 14px;
            margin-top: 5px;
        }

        .hero-container {
            width: 100%;
            height: 250px;
            overflow: hidden;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .hero-image {
            width: 100%;
            height: auto;
            min-height: 250px;
        }

        .stats-box {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }

        .stats-box span {
            font-weight: bold;
            margin: 0 10px;
            color: #1f2937;
            font-size: 13px;
        }

        .dia-container {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .dia-header {
            background-color: #eff6ff;
            border-left: 5px solid #3b82f6;
            padding: 10px 15px;
            margin-bottom: 15px;
        }

        .dia-numero {
            font-weight: bold;
            color: #2563eb;
            font-size: 18px;
        }

        .dia-titulo {
            font-weight: bold;
            font-size: 16px;
            margin-left: 10px;
        }

        .descripcion {
            text-align: justify;
            font-size: 14px;
        }

        strong {
            color: #111827;
        }

        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 class="title">Tu Itinerario Inteligente para {{$viaje->destino}}</h1>
        <p class="subtitle">Generado por VIAJA</p>
    </div>

    @if($imagenBase64)
    <div class="hero-container">
        <img src="{{ $imagenBase64 }}" class="hero-image">
    </div>
    @endif

    <div class="stats-box">
        <span>Destino: {{ $viaje->destino }}</span>
        <span>|</span>
        <span>{{ $viaje->noches }} Noches</span>
        <span>|</span>
        <span>{{ $viaje->personas }} Personas</span>
        <span>|</span>
        <span>Presupuesto: {{ $viaje->presupuesto }}€</span>
    </div>

    @foreach($viaje->dias as $dia)
    <div class="dia-container">
        <div class="dia-header">
            <span class="dia-numero">Día {{ $dia->numero_dia }}:</span>
            <span class="dia-titulo">{{ $dia->titulo }}</span>
        </div>
        <div class="descripcion">
            @php
            $textoLimpio = str_replace(['**', '☀️', '🌇', '🌙', '🏨'], '', $dia->descripcion);
            $textoLimpio = preg_replace(
            '/(MAÑANA:|TARDE:|NOCHE:|ALOJAMIENTO:)/',
            '<strong>$1</strong>',
            $textoLimpio
            );
            @endphp
            {!! nl2br(trim($textoLimpio)) !!}
        </div>
    </div>
    @endforeach

    <div class="footer">
        VIAJA - Itinerarios Inteligentes de Viaje | Proyecto TFC
    </div>
</body>

</html>