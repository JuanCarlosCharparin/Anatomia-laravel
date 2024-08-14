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
            /* Asegura que el cuerpo ocupe al menos la altura de la ventana */
            display: flex;
            flex-direction: column;
        }

        .header {
            text-align: center;
            font-size: 10px;
            margin-bottom: 10px;
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 10px;
        }

        h1.title {
            text-align: center;
            font-size: 18px;
            /* Tamaño de fuente para h1 */
            margin: 20px 0;
            color: white;
            background-color: #800000;
            padding: 5px;
            border-radius: 5px;
            text-decoration-color: #6f4f28;
            text-decoration-thickness: 2px;
            margin-left: auto;
            margin-right: auto;
        }

        h1.subtitle {
            text-align: center;
            font-size: 18px;
            /* Tamaño de fuente para h1 */
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
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 10px 0;
            border-top: 1px solid #000;
        }

        .content {
            flex: 1;
            /* Esto hace que el contenido ocupe todo el espacio disponible entre el header y el footer */
            padding: 20px;
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
            text-decoration: underline;
            /* Aplica el subrayado */
            color: #000000;
            /* Color del texto, puedes ajustarlo según tu preferencia */
            padding: 2px 4px;
            /* Espacio alrededor del texto (opcional) */
            font-weight: bold;
            /* Opcional, para hacer el texto más audaz */
            border-radius: 0;
            /* Elimina los bordes redondeados */
            background-color: transparent;
            /* Asegura que no haya color de fondo */
            font-size: 1.2em;
            
        }
    </style>
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
                            <td class="field"><span class="bold-heading">MED. SOLICITANTE:</span> {{ $medico }}
                            </td>
                            <td  class="field field-right">
                                <span class="bold-heading">N° INFORME:</span>
                                <span class="highlighted-number">{{ $informe_numero }}</span>
                            </td>


                        </tr>
                        <tr>
                            <td class="field"><span class="bold-heading">FECHA ENTRADA:</span> {{ $fecha_entrada }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Contenido específico para tipo de estudio Pap -->

            <section>
                <h1 class="subtitle">INFORME DE PAP</h1>
            </section>

            <div class="section">
                <p><span class="bold-heading">MATERIAL REMITIDO:</span> {{ $material_remitido }}</p>
                <p></p>
                <p><span class="bold-heading">ESTADO ESPÉCIMEN:</span>
                    @if (is_array($estado_especimen))
                        {{ implode(', ', array_map('htmlspecialchars', $estado_especimen)) }}
                    @else
                        {{ htmlspecialchars($estado_especimen) }}
                    @endif
                </p>
                <p></p>
                <p><span class="bold-heading">CÉLULAS PAVIMENTOSAS:</span>
                    @if (is_array($celulas_pavimentosas))
                        {{ implode(', ', array_map('htmlspecialchars', $celulas_pavimentosas)) }}
                    @else
                        {{ htmlspecialchars($celulas_pavimentosas) }}
                    @endif
                </p>
                <p></p>
                <p><span class="bold-heading">CÉLULAS CILÍNDRICAS:</span>
                    @if (is_array($celulas_cilindricas))
                        {{ implode(', ', array_map('htmlspecialchars', $celulas_cilindricas)) }}
                    @else
                        {{ htmlspecialchars($celulas_cilindricas) }}
                    @endif
                </p>
                <p></p>
                <p><span class="bold-heading">VALOR HORMONAL:</span> {{ $valor_hormonal }}</p>
                <p></p>
                <p><span class="bold-heading">MICROORGANISMOS:</span>
                    @if (is_array($microorganismos))
                        {{ implode(', ', array_map('htmlspecialchars', $microorganismos)) }}
                    @else
                        {{ htmlspecialchars($microorganismos) }}
                    @endif
                </p>
                <p></p>
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
                            <td class="field"><span class="bold-heading">MED. SOLICITANTE:</span> {{ $medico }}
                            </td>
                            <td  class="field field-right">
                                <span class="bold-heading">N° INFORME:</span>
                                <span class="highlighted-number">{{ $informe_numero }}</span>
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
                <p style="margin-bottom: 20px;"><span class="bold-heading">MATERIAL REMITIDO:</span>
                    {{ $material_remitido }}</p>
                <p><span class="bold-heading">TÉCNICA:</span>
                    @if (is_array($tecnica))
                        {{ implode(', ', array_map('htmlspecialchars', $tecnica)) }}
                    @else
                        {{ htmlspecialchars($tecnica) }}
                    @endif
                </p>
                <p></p>
                <p style="margin-bottom: 20px;"><span class="bold-heading">MACROSCOPIA:</span> {{ $macroscopia }}</p>
                <p style="margin-bottom: 20px;"><span class="bold-heading">MICROSCOPIA:</span> {{ $microscopia }}</p>
                <p style="margin-bottom: 20px;"><span class="bold-heading">DIAGNÓSTICO:</span> {{ $diagnostico }}</p>

                @if ($ampliar_informe != '')
                    <p style="margin-bottom: 20px;"><span class="bold-heading">AMPLIACIÓN:</span> {{ $ampliar_informe }}</p>
                @endif
            </div>
        @endif

    </div>


    <div class="footer">
        <p>Información confidencial - secreto médico - alcances del art. 156 del Código Penal. Paso de los Andes 3051 -
            Ciudad de Mendoza.</p>
        <p>Teléfonos (0261)4135011/(0261)4135021 - info@hospital.uncu.edu.ar/www.hospital.uncu.edu.ar / Powered by TCPDF
            (www.tcpdf.org)</p>
    </div>
</body>

</html>
