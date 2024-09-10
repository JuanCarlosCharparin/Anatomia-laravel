<!DOCTYPE html>
<html>

<head>
    @section('title', 'Anatomía-Patológica')
    <title>Comprobante de Impresión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative; /* Asegura que el footer se posicione correctamente */
        }

        .page-break {
            page-break-before: always;
        }

        .header {
            text-align: center;
            font-size: 10px;
            margin-bottom: 10px;
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 10px;
        }

        h1.title, h1.subtitle {
            text-align: center;
            font-size: 18px;
            margin: 20px 0;
            color: white;
            background-color: #800000;
            padding: 5px;
            border-radius: 5px;
            text-decoration-color: #6f4f28;
            text-decoration-thickness: 2px;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
        }

        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 20px 0; /* Ajusta el padding para acomodar las firmas y el texto del footer */
            box-sizing: border-box; /* Asegura que el padding se incluya en el cálculo del ancho total */
        }

        .firma {
            display: block;
            margin: 0 auto; /* Centra las imágenes horizontalmente dentro del footer */
            width: 150px;
            margin-bottom: 10px; /* Espacio entre la firma y el texto del footer */
        }

        .firma_cecilia {
            display: block;
            margin: 0 auto; /* Centra las imágenes horizontalmente dentro del footer */
            width: 230px;
            margin-bottom: 10px;
        }

        .content {
            flex: 1;
            padding: 20px;
            padding-bottom: 60px; /* Ajusta el padding-bottom para hacer espacio para el footer */
        }

        .sections-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .section {
            flex: 1 1 30%;
            box-sizing: border-box;
            width: 100%;
        }

        .section p {
            margin: 5px 0;
        }

        .bold-heading {
            font-weight: bold;
            font-size: 1.2em;
        }

        .highlighted-number {
            color: #000000;
            padding: 2px 4px;
            font-weight: bold;
            background-color: transparent;
            font-size: 1.2em;
        }

        .ampliacion {
            margin-bottom: 20px;
            font-size: 18px;
            text-align: center;
            padding: 10px;
            max-width: 80%;
            margin-left: auto;
            margin-right: auto;
            white-space: pre-line; /* Permite saltos de línea en el texto */
        }

        .key {
            font-weight: bold;
            margin: 0;
        }

        .value {
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="content">
        @if ($tipo_estudio === 3)
            <img src="{{ asset('img/Logo.jpeg') }}" alt="Logo">
            <section>
                <h1 class="title">CITOLOGIA EXFOLIATIVA ONCOLOGICA (PAP) - CALSIFICACION BETHESDA</h1>
            </section>

            <div class="sections-container">
                <div class="section">
                    <table class="inline-table">
                        <tr>
                            <td class="field"><span class="bold-heading">PACIENTE:</span> {{ $nombre }}</td>
                            <td class="field field-right"><span class="bold-heading">DNI:</span> {{ $dni }}</td>
                        </tr>
                        <tr>
                            <td class="field"><span class="bold-heading">MED. SOLICITANTE:</span> {{ $medico }}</td>
                            <td class="field field-right">
                                <span class="bold-heading">N° INFORME:</span>
                                <span class="highlighted-number">HU {{ $informe_numero }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field"><span class="bold-heading">FECHA ENTRADA:</span> {{ $fecha_entrada }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <section>
                <h1 class="subtitle">INFORME DE PAP</h1>
            </section>

            <div class="section">
                <p><span class="bold-heading">MATERIAL REMITIDO:</span> {{ $material_remitido }}</p>
                <p><span class="bold-heading">ESTADO ESPÉCIMEN:</span>
                    @if (is_array($estado_especimen))
                        {{ implode(', ', array_map('htmlspecialchars', $estado_especimen)) }}
                    @else
                        {{ htmlspecialchars($estado_especimen) }}
                    @endif
                </p>
                <p><span class="bold-heading">CÉLULAS PAVIMENTOSAS:</span>
                    @if (is_array($celulas_pavimentosas))
                        {{ implode(', ', array_map('htmlspecialchars', $celulas_pavimentosas)) }}
                    @else
                        {{ htmlspecialchars($celulas_pavimentosas) }}
                    @endif
                </p>
                <p><span class="bold-heading">CÉLULAS CILÍNDRICAS:</span>
                    @if (is_array($celulas_cilindricas))
                        {{ implode(', ', array_map('htmlspecialchars', $celulas_cilindricas)) }}
                    @else
                        {{ htmlspecialchars($celulas_cilindricas) }}
                    @endif
                </p>
                <p><span class="bold-heading">VALOR HORMONAL:</span> {{ $valor_hormonal }}</p>
                <p><span class="bold-heading">MICROORGANISMOS:</span>
                    @if (is_array($microorganismos))
                        {{ implode(', ', array_map('htmlspecialchars', $microorganismos)) }}
                    @else
                        {{ htmlspecialchars($microorganismos) }}
                    @endif
                </p>
                <p><span class="bold-heading">RESULTADO:</span>
                    @if (is_array($resultado))
                        {{ implode(', ', array_map('htmlspecialchars', $resultado)) }}
                    @else
                        {{ htmlspecialchars($resultado) }}
                    @endif
                </p>
            </div>
        @else
            <img src="{{ asset('img/Logo.jpeg') }}" alt="Logo">
            <section>
                <h1 class="subtitle">LABORATORIO DE ANATOMÍA PATOLÓGICA</h1>
            </section>

            <div class="sections-container">
                <div class="section">
                    <table class="inline-table">
                        <tr>
                            <td class="field"><span class="bold-heading">PACIENTE:</span> {{ $nombre }}</td>
                            <td class="field field-right"><span class="bold-heading">DNI:</span> {{ $dni }}</td>
                        </tr>
                        <tr>
                            <td class="field"><span class="bold-heading">MED. SOLICITANTE:</span> {{ $medico }}</td>
                            <td class="field field-right">
                                <span class="bold-heading">N° INFORME:</span>
                                <span class="highlighted-number">HU {{ $informe_numero }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field"><span class="bold-heading">FECHA ENTRADA:</span> {{ $fecha_entrada }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="section">
                <h1 class="title">INFORME DE ANATOMÍA PATOLÓGICA</h1>
                <p style="margin-bottom: 20px;">
                    <span class="bold-heading">MATERIAL REMITIDO:</span>
                    <ul>
                        @php
                            // Convertir la cadena de texto en un array usando comas como delimitador
                            $materials = explode(',', $material_remitido);
                        @endphp
                        @foreach($materials as $material)
                            @if(trim($material)) <!-- Evita mostrar elementos vacíos -->
                                <li>{{ htmlspecialchars(trim($material)) }}</li>
                            @endif
                        @endforeach
                    </ul>
                </p>
                <p><span class="bold-heading">TÉCNICA:</span>
                    @if (is_array($tecnica))
                        {{ implode(', ', array_map('htmlspecialchars', $tecnica)) }}
                    @else
                        {{ htmlspecialchars($tecnica) }}
                    @endif
                </p>
                <p style="margin-bottom: 20px;">
                    <span class="bold-heading">MACROSCOPIA:</span> 
                    <span style="display: block; margin-left: 80px; margin-top: 10px; margin-bottom: 10px;">{!! nl2br(e($macroscopia)) !!}</span>
                </p>
                
                <p style="margin-bottom: 20px;">
                    <span class="bold-heading">MICROSCOPIA:</span> 
                    <span style="display: block; margin-left: 80px; margin-top: 10px; margin-bottom: 10px;">{!! nl2br(e($microscopia)) !!}</span>
                </p>
                <p style="margin-bottom: 20px;">
                    <span class="bold-heading">DIAGNÓSTICO:</span> 
                    <span style="display: block; margin-left: 80px; margin-top: 10px; margin-bottom: 10px; font-weight: bold;">{!! nl2br(e($diagnostico)) !!}</span>
                </p>
                <p style="margin-bottom: 20px;">
                    <span class="bold-heading">NOTAS:</span> 
                    <span style="display: block; margin-left: 80px; margin-top: 10px; margin-bottom: 10px;">{!! nl2br(e($observacion)) !!}</span>
                </p>
            

                @if ($ampliar_informe != '')
                    <div class="ampliacion">
                        <p class="key">AMPLIACIÓN:</p>
                        <p class="value">{{ $ampliar_informe }}</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    

    <div class="footer">


        @if(isset($createdDetalle) && $createdDetalle)
            <img class="firma" src="{{ asset('img/firma_fernanda-j.jpg') }}" alt="Firma Electrónica">
        @endif

        @if(isset($createdPap) && $createdPap)
            <img class="firma_cecilia" src="{{ asset('img/firma_cecilia.jpg') }}" alt="Firma Electrónica">
        @endif

        <p>Firmado electrónicamente por la Dra. {{ $createdPap ?? '' }} {{ $createdDetalle ?? '' }} - Matrícula: {{ $matriculaPap ?? '' }} {{ $matriculaDetalle ?? '' }} - Información confidencial - secreto médico - alcances del art. 156 del Código Penal  
            y validado en sistema HIS-Alephoo según art. 5 de la Ley 25.506 "Firma Digital" - Paso de los Andes 3051 - Ciudad de Mendoza.</p>
        <p>Teléfonos (0261)4135011/(0261)4135021 - info@hospital.uncu.edu.ar/www.hospital.uncu.edu.ar </p>
    </div>

    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->text(270, 820, "Pág $PAGE_NUM de $PAGE_COUNT", $font, 10);
            ');
        }
    	</script>
</body>
