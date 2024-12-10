<!DOCTYPE html>
<html>

<head>
    @section('title', 'Anatomía-Patológica')
    <title>Comprobante de Impresión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
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
            width: 100px;
            margin-bottom: 5px; /* Espacio entre la firma y el texto del footer */
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
                <h1 class="title">CITOLOGIA EXFOLIATIVA ONCOLOGICA (PAP) - CLASIFICACION BETHESDA</h1>
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
                @if (!empty($estado_especimen))
                    <p><span class="bold-heading">ESTADO ESPÉCIMEN:</span>
                        @if (is_array($estado_especimen))
                            {{ implode(', ', array_map('htmlspecialchars', $estado_especimen)) }}
                        @else
                            {{ htmlspecialchars($estado_especimen) }}
                        @endif
                    </p>
                @endif

                @if (!empty($celulas_pavimentosas))
                    <p><span class="bold-heading">CÉLULAS PAVIMENTOSAS:</span>
                        @if (is_array($celulas_pavimentosas))
                            {{ implode(', ', array_map('htmlspecialchars', $celulas_pavimentosas)) }}
                        @else
                            {{ htmlspecialchars($celulas_pavimentosas) }}
                        @endif
                    </p>
                @endif

                @if (!empty($celulas_cilindricas))
                    <p><span class="bold-heading">CÉLULAS CILÍNDRICAS:</span>
                        @if (is_array($celulas_cilindricas))
                            {{ implode(', ', array_map('htmlspecialchars', $celulas_cilindricas)) }}
                        @else
                            {{ htmlspecialchars($celulas_cilindricas) }}
                        @endif
                    </p>
                @endif

                @if (!empty($valor_hormonal))
                    <p><span class="bold-heading">VALOR HORMONAL:</span> {{ $valor_hormonal }}</p>
                @endif

                @if (!empty($cambios_reactivos))
                    <p><span class="bold-heading">CAMBIOS REACTIVOS:</span>
                        @if (is_array($cambios_reactivos))
                            {{ implode(', ', array_map('htmlspecialchars', $cambios_reactivos)) }}
                        @else
                            {{ htmlspecialchars($cambios_reactivos) }}
                        @endif
                    </p>
                @endif

                @if (!empty($cambios_asoc_celula_pavimentosa))
                    <p><span class="bold-heading">CAMBIOS ASOC. CÉLULA PAVIMENTOSA:</span>
                        @if (is_array($cambios_asoc_celula_pavimentosa))
                            {{ implode(', ', array_map('htmlspecialchars', $cambios_asoc_celula_pavimentosa)) }}
                        @else
                            {{ htmlspecialchars($cambios_asoc_celula_pavimentosa) }}
                        @endif
                    </p>
                @endif

                @if (!empty($cambios_celula_glandulares))
                    <p><span class="bold-heading">CAMBIOS CÉLULA GLANDULARES:</span> {{ $cambios_celula_glandulares }}</p>
                @endif

                @if (!empty($celula_metaplastica))
                    <p><span class="bold-heading">CÉLULA METAPLÁSTICA:</span>
                        @if (is_array($celula_metaplastica))
                            {{ implode(', ', array_map('htmlspecialchars', $celula_metaplastica)) }}
                        @else
                            {{ htmlspecialchars($celula_metaplastica) }}
                        @endif
                    </p>
                @endif

                @if (!empty($otras_neo_malignas))
                    <p><span class="bold-heading">OTRAS NEOPLASIAS MALIGNAS:</span> {{ $otras_neo_malignas }}</p>
                @endif

                @if (!empty($toma))
                    <p><span class="bold-heading">TOMA:</span>
                        @if (is_array($toma))
                            {{ implode(', ', array_map('htmlspecialchars', $toma)) }}
                        @else
                            {{ htmlspecialchars($toma) }}
                        @endif
                    </p>
                @endif

                @if (!empty($recomendaciones))
                    <p><span class="bold-heading">RECOMENDACIONES:</span>
                        @if (is_array($recomendaciones))
                            {{ implode(', ', array_map('htmlspecialchars', $recomendaciones)) }}
                        @else
                            {{ htmlspecialchars($recomendaciones) }}
                        @endif
                    </p>
                @endif

                @if (!empty($microorganismos))
                    <p><span class="bold-heading">MICROORGANISMOS:</span>
                        @if (is_array($microorganismos))
                            {{ implode(', ', array_map('htmlspecialchars', $microorganismos)) }}
                        @else
                            {{ htmlspecialchars($microorganismos) }}
                        @endif
                    </p>
                @endif

                @if (!empty($resultado))
                    <p><span class="bold-heading">RESULTADO:</span>
                        @if (is_array($resultado))
                            {{ implode(', ', array_map('htmlspecialchars', $resultado)) }}
                        @else
                            {{ htmlspecialchars($resultado) }}
                        @endif
                    </p>
                @endif
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
                            <td class="field field-right"><span class="bold-heading" style="margin-left: 20px;">DNI:</span> {{ $dni }}</td>
                        </tr>
                        <tr>
                            <td class="field"><span class="bold-heading">MED. SOLICITANTE:</span> {{ $medico }}</td>
                            <td class="field field-right">
                                <span class="bold-heading" style="margin-left: 20px;">N° INFORME:</span>
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
                <p style="margin-bottom: 5px;">
                    <span class="bold-heading">MATERIAL REMITIDO:</span>
                    @php
                        // Convertir la cadena de texto en un array usando comas como delimitador
                        $materials = explode(',', $material_remitido);
                        // Filtrar elementos vacíos y eliminar espacios extra
                        $materials = array_filter(array_map('trim', $materials));
                    @endphp
                    {{ implode(', ', $materials) }}
                </p>
                <p><span class="bold-heading">TÉCNICA:</span>
                    @if (is_array($tecnica))
                        {{ implode(', ', array_map('htmlspecialchars', $tecnica)) }}
                    @else
                        {{ htmlspecialchars($tecnica) }}
                    @endif
                </p>
                @if(!empty($macroscopia))
                    <p style="margin-bottom: 5px; margin-top: 5px;">
                        <span class="bold-heading" style="margin-bottom: 10px; display: block;">MACROSCOPIA:</span>
                        <span style="display: block; margin-left: 30px; margin-top: 10px; margin-bottom: 10px;">{!! nl2br(e($macroscopia)) !!}</span>
                    </p>
                @endif

                @if(!empty($microscopia))
                <p style="margin-bottom: 5px; margin-top: 5px;">
                        <span class="bold-heading" style="margin-bottom: 10px; display: block;">MICROSCOPIA:</span>
                        <span style="display: block; margin-left: 30px; margin-top: 10px; margin-bottom: 10px;">{!! nl2br(e($microscopia)) !!}</span>
                    </p>
                @endif

                @if(!empty($diagnostico))
                    <p style="margin-bottom: 5px; margin-top: 5px;">
                        <span class="bold-heading" style="margin-bottom: 10px; display: block;">DIAGNÓSTICO:</span>
                        <span style="display: block; margin-left: 30px; margin-top: 10px; margin-bottom: 10px; font-weight: bold;">{!! nl2br(e($diagnostico)) !!}</span>
                    </p>
                @endif

                @if(!empty($observacion))
                    <p style="margin-bottom: 5px; margin-top: 5px;">
                        <span class="bold-heading" style="margin-bottom: 10px; display: block;">NOTAS:</span>
                        <span style="display: block; margin-left: 30px; margin-top: 10px; margin-bottom: 10px;">{!! nl2br(e($observacion)) !!}</span>
                    </p>
                @endif
            

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


        @if(isset($createdDetalle) && $createdDetalle == "María Fernanda Contreras")
            <img class="firma" src="{{ asset('img/firma_fernanda-j.jpg') }}" alt="Firma Electrónica">
        @elseif(isset($createdDetalle) && $createdDetalle == "María José Fiorita")
            <img class="firma" src="{{ asset('img/firma-fiorita.jpg') }}" alt="Firma Electrónica">
        @elseif(isset($createdDetalle) && $createdDetalle == "Adriana Cecilia Torres")
            <img class="firma_cecilia" src="{{ asset('img/firma_cecilia.jpg') }}" alt="Firma Electrónica">
        @endif

        @if(isset($createdPap) && $createdPap == "Adriana Cecilia Torres")
            <img class="firma_cecilia" src="{{ asset('img/firma_cecilia.jpg') }}" alt="Firma Electrónica">
        @elseif(isset($createdPap) && $createdPap == "María Fernanda Contreras")
            <img class="firma" src="{{ asset('img/firma_fernanda-j.jpg') }}" alt="Firma Electrónica">
        @elseif(isset($createdPap) && $createdPap == "María José Fiorita")
            <img class="firma" src="{{ asset('img/firma-fiorita.jpg') }}" alt="Firma Electrónica">
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
