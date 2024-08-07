<head>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<x-app-layout>
    <x-slot name="title">Estudios</x-slot>
    @section('title', 'Anatomia-Patologica')
    <div class="container mt-4">

    <div class="container-fluid mt-4">
        <h1>Estudios</h1>
        
        <form method="GET" action="{{ route('estudios.index') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search_general" value="{{ $searchGeneral }}" class="form-control" placeholder="Buscar general">
                <button class="btn btn-primary" type="submit">Buscar</button>

                @if($searchGeneral)
                    <a href="{{ route('estudios.index') }}" class="btn btn-secondary">×</a>
                @endif

                <input type="number" name="search_nro_servicio" value="{{ $searchNroServicio }}" class="form-control" placeholder="Buscar por N° Servicio">
                <button class="btn btn-primary" type="submit">Buscar</button>

                @if($searchNroServicio)
                    <a href="{{ route('estudios.index') }}" class="btn btn-secondary">×</a>
                @endif
            </div>
        </form>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <p>Total de registros: {{ $estudios->total() }}</p>

        <table class="table table-responsive" style="width: 100%;">
            <thead>
                <tr>
                    <th>N° Servicio</th>
                    <th style="width: 15%;">Servicio</th>
                    <th style="width: 20%;">Tipo de Estudio</th>
                    <th style="width: 10%;">Estado</th>
                    <th style="width: 25%;">Paciente</th>
                    <th style="width: 25%;">DNI</th>
                    <th style="width: 15%;">Obra Social</th>
                    <th style="width: 25%;">Diagnóstico</th>
                    <th style="width: 30%;">Fecha de Carga</th> <!-- Ancho aumentado aquí -->
                    <th style="width: 20%;">Profesional</th>
                    <th style="width: 20%;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($estudios as $estudio)
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>N° Servicio</th>
                        <th style="width: 5%;">Servicio</th>
                        <th style="width: 5%;">Tipo Estudio</th>
                        <th style="width: 5%;">Estado</th>
                        <th class="paciente" style="width: 10%;">Paciente</th>
                        <th style="width: 10%;">DNI</th>
                        <th style="width: 5%;">Obra Social</th>
                        <th class="diagnostico" style="width: 20%;">Diagnóstico</th>
                        <th style="width: 15%;">Fecha Carga</th>
                        <th style="width: 15%;">Profesional</th>
                        <th style="width: 15%;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($estudios as $estudio)
                        <tr>
                            <td>{{ $estudio->nro_servicio }}</td>
                            <td>{{ $estudio->servicio }}</td>
                            <td>{{ $estudio->tipo_estudio }}</td>
                            <td>{{ $estudio->estado }}</td>
                            <td class="paciente" style="width: 10%">{{ $estudio->paciente }}</td>
                            <td>{{ $estudio->documento }}</td>
                            <td>{{ $estudio->obra_social }}</td>
                            <td class="diagnostico">{{ $estudio->diagnostico }}</td>
                            <td>{{ \Carbon\Carbon::parse($estudio->fecha_carga)->format('d-m-Y') }}</td>
                            <td>{{ $estudio->profesional }}</td>
                            <td>
                                <a href="{{ route('estudios.edit', $estudio->nro_servicio) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a href="{{ route('exportar.datos', $estudio->nro_servicio) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-download"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $estudios->appends(['search_general' => $searchGeneral, 'search_nro_servicio' => $searchNroServicio])->links() }}
    </div>
</x-app-layout>

<style>
    .table-responsive {
        overflow-x: auto !important;
    }

    .table {
        width: 100% !important;
    }

    .table th, .table td {
        white-space: nowrap !important;
        vertical-align: middle !important;
    }

    .diagnostico {
        max-width: 25ch !important;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .paciente {
        max-width: 20ch !important;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .btn .fas {
        margin-right: 0;
    }
</style>
