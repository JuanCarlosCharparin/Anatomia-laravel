<head>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<x-app-layout>
    <x-slot name="title">Estudios</x-slot>
    @section('title', 'Anatomía-Patológica')
    <div class="mt-4">

        <div class="mt-4">
            <h1>Estudios</h1>

            <form method="GET" action="{{ route('estudios.index') }}" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search_general" value="{{ $searchGeneral }}" class="form-control"
                        placeholder="Buscar general">
                    <button class="btn btn-primary" type="submit">Buscar</button>

                    @if($searchGeneral)
                        <a href="{{ route('estudios.index') }}" class="btn btn-secondary">×</a>
                    @endif

                    <input type="number" name="search_nro_servicio" value="{{ $searchNroServicio }}"
                        class="form-control" placeholder="Buscar por N° Servicio">
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

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>N° Serv.</th>
                            <th class="servicio" style="width: 5%;">Servicio</th>
                            <th style="width: 5%;">Tipo Estudio</th>
                            <th class="estado" style="width: 5%;">Estado</th>
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
                                <td class="servicio">{{ $estudio->servicio }}</td>
                                <td >{{ $estudio->tipo_estudio }}</td>
                                <td class="estado">{{ $estudio->estado }}</td>
                                <td class="paciente" style="width: 10%">{{ $estudio->paciente }}</td>
                                <td>{{ $estudio->documento }}</td>
                                <td class="obrasocial">{{ $estudio->obra_social }}</td>
                                <td class="diagnostico">{{ $estudio->diagnostico }}</td>
                                <td>{{ \Carbon\Carbon::parse($estudio->fecha_carga)->format('d-m-Y') }}</td>
                                <td>{{ $estudio->profesional }}</td>
                                <td>
                                    @php
                                        $user = Auth::user();
                                        $roles = $user->getRoleNames()->toArray();
                                    @endphp

                                    @if (!in_array('visualizacion', $roles))
                                        <a href="{{ route('estudios.edit', $estudio->nro_servicio) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif

                                    <a href="{{ route('exportar.datos', $estudio->nro_servicio) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="fas fa-download"></i>
                                    </a>

                                    @if ($estudio->estado == 'creado')
                                        @if (in_array('admin', $roles))
                                            <a href="{{ route('estudios.modify', $estudio->nro_servicio) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    @endif

                                 
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

    .table th,
    .table td {
        white-space: nowrap !important;
        vertical-align: middle !important;
    }

    .diagnostico {
        max-width: 23ch !important;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .obrasocial {
        max-width: 10ch !important;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .paciente {
        max-width: 19ch !important;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .estado {
        max-width: 21ch !important;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .servicio {
        max-width: 16ch !important;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .btn .fas {
        margin-right: 0;
    }

    .mt-4 {
        margin-top: 1.5rem !important;
    }
</style>