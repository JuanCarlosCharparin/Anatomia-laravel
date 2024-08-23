<head>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<x-app-layout>
    @section('title', 'Anatomía-Patológica')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="mt-4">
        <h1>Sistema de Anatomía Patológica</h1>
        <p>¡Hola {{ auth()->user()->name }}!</p>
        <p>Si necesitas encontrar el informe de un estudio de un paciente en particular, dirígete a:</p>
        <ul>
            <li>> Estudios</li>
        </ul>
        <p>Si cuentas con el número de servicio del estudio, ingrésalo únicamente en el primer buscador (Buscar por N° Servicio).</p>
        <p>Si tienes los datos del paciente (DNI, nombres o apellidos), ingrésalos en el buscador "Buscar Paciente o DNI". Puedes introducir el DNI del paciente o el nombre completo (incluyendo el segundo nombre, si lo tiene).</p>
        <p>Si deseas filtrar todos los estudios de un mismo tipo de estudio puedes realizar la busqueda en "Buscar Tipo Estudio", por ejemplo: "Biopsia", "Pap", "Citologia" o "Intraoperatorio"</p>
    </div>
</x-app-layout>