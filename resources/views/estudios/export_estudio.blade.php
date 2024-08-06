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
            font-size: 18px;
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
            font-size: 12px;
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
            font-size: 14px;
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
        <div class="section">
            <p><span class="bold-heading">FECHA SALIDA:</span> {{ $fecha_salida }}</p>
        </div>

        <div class="section">
            <h2 class="title" style="text-align: center;">INFORME DE ANATOMÍA PATOLÓGICA</h2>
            <p><span class="field">MATERIAL REMITIDO:</span> {{ $material_remitido }}</p>
            <p><span class="field">TÉCNICA:</span> {{ $tecnica }}</p>
            <p><span class="field">MACROSCOPIA:</span> {{ $macroscopia }}</p>
            <p><span class="field">MICROSCOPIA:</span> {{ $microscopia }}</p>
            <p><span class="field">CÓDIGO:</span> {{ $codigo }}</p>
            <p><span class="field">DIAGNÓSTICO:</span> {{ $diagnostico }}</p>
            <p><span class="field">NOTA:</span> {{ $nota }}</p>
        </div>
    </div>
    <div class="footer">
        <p>Información confidencial - secreto médico - alcances del art. 156 del Código Penal</p>
        <p>Paso de los Andes 3051 - Ciudad de Mendoza.</p>
        <p>Teléfonos (0261)4135011/(0261)4135021 - info@hospital.uncu.edu.ar/www.hospital.uncu.edu.ar</p>
        <p>Powered by TCPDF (www.tcpdf.org)</p>
    </div>
</body>

</html>