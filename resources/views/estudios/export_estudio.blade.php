<!DOCTYPE html>
<html>

<head>
    <title>Comprobante de Impresión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh; /* Asegura que el cuerpo ocupe al menos la altura de la ventana */
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
            font-size: 18px; /* Tamaño de fuente para h1 */
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
            font-size: 18px; /* Tamaño de fuente para h1 */
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
            margin-left: auto;
            margin-right: auto;
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
            flex: 1; /* Esto hace que el contenido ocupe todo el espacio disponible entre el header y el footer */
            padding: 20px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section h2 {
            text-align: left;
            font-size: 18px; /* Igualar tamaño de fuente con h1 */
            margin-bottom: 10px;
        }

        .section p {
            margin: 5px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .bold-heading {
            font-weight: bold;
            font-size: 1.2em; /* Ajusta el tamaño del texto a aproximadamente el tamaño de un h2 */
        }

        .inline-table {
            width: 100%;
            border-collapse: collapse;
        }

        .inline-table td {
            padding: 0 10px;
            vertical-align: top;
        }

        .inline-table .field {
            width: 50%; /* Ajusta el ancho de las celdas */
        }
    </style>
</head>

<body>
    <div class="content">
        @if($tipo_estudio === 3)

            <img src="{{ asset('img/Logo.jpeg') }}" alt="Logo">
            <section>
                <h1 class="title">CITOLOGIA EXFOLIATIVA ONCOLOGICA (PAP) - CALSIFICACION BETHESDA</h1>
            </section>

            <div class="section">
                <p><span class="bold-heading">PACIENTE:</span> {{ $nombre }}</p>
            </div>

            <div class="section">
                <p><span class="bold-heading">DNI:</span> {{ $dni }}</p>
            </div>

            <div class="section">
                <p><span class="bold-heading">MEDICO SOLICITANTE:</span> {{ $medico }}</p>
            </div>

            <div class="section">
                <p><span class="bold-heading">NUMERO DE INFORME:</span> {{ $informe_numero }}</p>
            </div>

            <div class="section">
                <p><span class="bold-heading">FECHA ENTRADA:</span> {{ $fecha_entrada }}</p>
            </div>

            <!-- Contenido específico para tipo de estudio Pap -->
            <div class="section">
                <p><span class="bold-heading">FECHA DE SALIDA:</span> {{ $fecha_pap_finalizado }}</p>
            </div>

            <section>
                <h1 class="subtitle">INFORME DE PAP</h1>
            </section>

            <div class="section">
                <p><span class="bold-heading">MATERIAL REMITIDO:</span> {{ $material_remitido }}</p>
                <p></p>
                <p><span class="bold-heading">ESTADO ESPÉCIMEN:</span>
                    @if(is_array($estado_especimen))
                        {{ implode(', ', array_map('htmlspecialchars', $estado_especimen)) }}
                    @else
                        {{ htmlspecialchars($estado_especimen) }}
                    @endif
                </p>
                <p></p>
                <p><span class="bold-heading">CÉLULAS PAVIMENTOSAS:</span>
                    @if(is_array($celulas_pavimentosas))
                        {{ implode(', ', array_map('htmlspecialchars', $celulas_pavimentosas)) }}
                    @else
                        {{ htmlspecialchars($celulas_pavimentosas) }}
                    @endif
                </p>
                <p></p>
                <p><span class="bold-heading">CÉLULAS CILÍNDRICAS:</span>
                    @if(is_array($celulas_cilindricas))
                        {{ implode(', ', array_map('htmlspecialchars', $celulas_cilindricas)) }}
                    @else
                        {{ htmlspecialchars($celulas_cilindricas) }}
                    @endif
                </p>
                <p></p>
                <p><span class="bold-heading">VALOR HORMONAL:</span> {{ $valor_hormonal }}</p>
                <p></p>
                <p><span class="bold-heading">MICROORGANISMOS:</span>
                    @if(is_array($microorganismos))
                        {{ implode(', ', array_map('htmlspecialchars', $microorganismos)) }}
                    @else
                        {{ htmlspecialchars($microorganismos) }}
                    @endif
                </p>
                <p></p>
                <p><span class="bold-heading">RESULTADO:</span>
                    @if(is_array($resultado))
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
            
            <div class="section">
                <p><span class="bold-heading">PACIENTE:</span> {{ $nombre }}</p>
            </div>

            <div class="section">
                <p><span class="bold-heading">DNI:</span> {{ $dni }}</p>
            </div>

            <div class="section">
                <p><span class="bold-heading">MEDICO SOLICITANTE:</span> {{ $medico }}</p>
            </div>

            <div class="section">
                <p><span class="bold-heading">NUMERO DE INFORME:</span> {{ $informe_numero }}</p>
            </div>

            <div class="section">
                <p><span class="bold-heading">FECHA ENTRADA:</span> {{ $fecha_entrada }}</p>
            </div>

            <!-- Contenido para otros tipos de estudio -->
            <div class="section">
                <p><span class="bold-heading">FECHA DE SALIDA:</span> {{ $fecha_estudio_finalizado }}</p>
            </div>

            <div class="section">
                <h1 class="title">INFORME DE ANATOMÍA PATOLÓGICA</h1>
                <p style="margin-bottom: 20px;"><span class="bold-heading">MATERIAL REMITIDO:</span> {{ $material_remitido }}</p>
                <p style="margin-bottom: 20px;"><span class="bold-heading">TÉCNICA:</span> {{ $tecnica }}</p>
                <p style="margin-bottom: 20px;"><span class="bold-heading">MACROSCOPIA:</span> {{ $macroscopia }}</p>
                <p style="margin-bottom: 20px;"><span class="bold-heading">MICROSCOPIA:</span> {{ $microscopia }}</p>
                <p style="margin-bottom: 20px;"><span class="bold-heading">DIAGNÓSTICO:</span> {{ $diagnostico }}</p>
            </div>
        @endif

    </div>
    

    <div class="footer">
        <p>Información confidencial - secreto médico - alcances del art. 156 del Código Penal</p>
        <p>Paso de los Andes 3051 - Ciudad de Mendoza.</p>
        <p>Teléfonos (0261)4135011/(0261)4135021 - info@hospital.uncu.edu.ar/www.hospital.uncu.edu.ar</p>
        <p>Powered by TCPDF (www.tcpdf.org)</p>
    </div>
</body>

</html>
