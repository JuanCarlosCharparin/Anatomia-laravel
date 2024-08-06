<!DOCTYPE html>
<html>

<head>
    <title>Comprobante de Impresión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
        }

        h2.title {
            text-align: center;
            font-size: 18px; /* Igualar tamaño de fuente con h1 */
            margin: 20px 0;
            color: white;
            background-color: #800000;
            padding: 5px;
            border-radius: 5px;
            text-decoration-color: #6f4f28;
            text-decoration-thickness: 2px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-bottom: 20px;
        }

        .content {
            margin: 0 auto;
            width: 80%;
            font-size: 12px;
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

        .section p {
            margin: 0;
            /* Elimina el margen por defecto del párrafo */
        }

        .bold-heading {
            font-weight: bold;
            /* Hace el texto en negrita */
            font-size: 1.2em;
            /* Ajusta el tamaño del texto a aproximadamente el tamaño de un h2 */
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
            width: 50%;
            /* Ajusta el ancho de las celdas */
        }
    </style>
</head>

<body>
        @if($tipo_estudio === 3)

            <img src="{{ asset('img/Logo.jpeg') }}" alt="Logo">
            <section>
                <h1 class="title">CITOLOGIA EXFOLIATIVA ONCOLOGICA (PAP) - CALSIFICACION BETHESDA</h1>
            </section>
            <div class="content">
            
            <div class="section">
                <table class="inline-table">
                    <tr>
                        <td class="field"><span class="bold-heading">PACIENTE:</span> {{ $nombre }}</td>
                        <td class="field"><span class="bold-heading">DNI:</span> {{ $dni }}</td>
                    </tr>
                </table>
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
            <!-- Contenido específico para tipo de estudio 3 -->
            <div class="section">
                <p><span class="bold-heading">FECHA DE SALIDA:</span> {{ $fecha_pap_finalizado }}</p>
            </div>

            <div class="section">
                <h1 class="title" style="text-align: center;">INFORME DE PAP</h1>
                <p><span class="field">MATERIAL REMITIDO:</span> {{ $material_remitido }}</p>
                <p><span class="field">ESTADO ESPÉCIMEN:</span>
                    @if(is_array($estado_especimen))
                        <ul>
                            @foreach($estado_especimen as $estadoe)
                                <li>{{ htmlspecialchars($estadoe) }}</li>
                            @endforeach
                        </ul>
                    @else
                        {{ htmlspecialchars($estado_especimen) }}
                    @endif
                </p>
                <p><span class="field">CÉLULAS PAVIMENTOSAS:</span>
                    @if(is_array($celulas_pavimentosas))
                        <ul>
                            @foreach($celulas_pavimentosas as $celula)
                                <li>{{ htmlspecialchars($celula) }}</li>
                            @endforeach
                        </ul>
                    @else
                        {{ htmlspecialchars($celulas_pavimentosas) }}
                    @endif
                </p>
                <p><span class="field">CÉLULAS CILÍNDRICAS:</span>
                    @if(is_array($celulas_cilindricas))
                        <ul>
                            @foreach($celulas_cilindricas as $celula)
                                <li>{{ htmlspecialchars($celula) }}</li>
                            @endforeach
                        </ul>
                    @else
                        {{ htmlspecialchars($celulas_cilindricas) }}
                    @endif
                </p>
                <p><span class="field">VALOR HORMONAL:</span> {{ $valor_hormonal }}</p>
                
                <p><span class="field">MICROORGANISMOS:</span>
                    @if(is_array($microorganismos))
                        <ul>
                            @foreach($microorganismos as $microorganismo)
                                <li>{{ htmlspecialchars($microorganismo) }}</li>
                            @endforeach
                        </ul>
                    @else
                        {{ htmlspecialchars($microorganismos) }}
                    @endif
                </p>
                <p><span class="field">RESULTADO:</span>
                    @if(is_array($resultado))
                        <ul>
                            @foreach($resultado as $item)
                                <li>{{ htmlspecialchars($item) }}</li>
                            @endforeach
                        </ul>
                    @else
                        {{ htmlspecialchars($resultado) }}
                    @endif
                </p>
            </div>
        @else
            <img src="{{ asset('img/Logo.jpeg') }}" alt="Logo">
            <section>
                <h1 class="title">LABORATORIO DE ANATOMÍA PATOLÓGICA</h1>
            </section>
            <div class="content">
            
            <div class="section">
                <table class="inline-table">
                    <tr>
                        <td class="field"><span class="bold-heading">PACIENTE:</span> {{ $nombre }}</td>
                        <td class="field"><span class="bold-heading">DNI:</span> {{ $dni }}</td>
                    </tr>
                </table>
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
                <h1 class="title" style="text-align: center;">INFORME DE ANATOMÍA PATOLÓGICA</h1>
                <p style="margin-bottom: 20px;"><span class="field">MATERIAL REMITIDO:</span> {{ $material_remitido }}</p>
                <p style="margin-bottom: 20px;"><span class="field">TÉCNICA:</span> {{ $tecnica }}</p>
                <p style="margin-bottom: 20px;"><span class="field">MACROSCOPIA:</span> {{ $macroscopia }}</p>
                <p style="margin-bottom: 20px;"><span class="field">MICROSCOPIA:</span> {{ $microscopia }}</p>
                <p style="margin-bottom: 20px;"><span class="field">DIAGNÓSTICO:</span> {{ $diagnostico }}</p>
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