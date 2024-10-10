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
                <input type="hidden" name="page" value="{{ request()->input('page') }}">
            
                <!-- Primera fila de filtros -->
                <div class="input-group mb-3">
                    <input type="number" name="search_nro_servicio" value="{{ request()->input('search_nro_servicio') }}"
                        class="form-control" placeholder="Buscar N° Servicio">

                    @if (request()->input('search_nro_servicio'))
                        <a href="{{ route('estudios.index') }}" class="btn btn-secondary">×</a>
                    @endif

                    <input type="text" name="search_servicio" value="{{ request()->input('search_servicio') }}" class="form-control"
                        placeholder="Buscar Servicio">

                    @if (request()->input('search_servicio'))
                        <a href="{{ route('estudios.index') }}" class="btn btn-secondary">×</a>
                    @endif

                    <input type="text" name="search_tipo_estudio" value="{{ request()->input('search_tipo_estudio') }}"
                        class="form-control" placeholder="Buscar Tipo Estudio">

                    @if (request()->input('search_tipo_estudio'))
                        <a href="{{ route('estudios.index') }}" class="btn btn-secondary">×</a>
                    @endif

                    <input type="text" name="search_estado" value="{{ request()->input('search_estado') }}" class="form-control"
                        placeholder="Buscar Estado">

                    @if (request()->input('search_estado'))
                        <a href="{{ route('estudios.index') }}" class="btn btn-secondary">×</a>
                    @endif

                    <input type="text" name="search_paciente" value="{{ request()->input('search_paciente') }}" class="form-control"
                        placeholder="Buscar Paciente o DNI">

                    @if (request()->input('search_paciente'))
                        <a href="{{ route('estudios.index') }}" class="btn btn-secondary">×</a>
                    @endif

                    <input type="text" name="search_profesional" value="{{ request()->input('search_profesional') }}"
                        class="form-control" placeholder="Buscar Profesional">

                    @if (request()->input('search_profesional'))
                        <a href="{{ route('estudios.index') }}" class="btn btn-secondary">×</a>
                    @endif

                    <input type="text" name="search_obra_social" value="{{ request()->input('search_obra_social') }}"
                        class="form-control" placeholder="Buscar Obra Social">

                    @if (request()->input('search_obra_social'))
                        <a href="{{ route('estudios.index') }}" class="btn btn-secondary">×</a>
                    @endif
                </div>

                <!-- Segunda fila de filtros -->
                <div class="input-group mb-3 d-flex justify-content-between">
                    <!-- Filtro "Desde" -->
                    <div class="input-group-prepend">
                        <span class="input-group-text">Desde</span>
                    </div>
                    <input type="date" name="search_desde" value="{{ request()->input('search_desde') }}" class="form-control col-3"
                        placeholder="Buscar por fecha de inicio">

                    @if (request()->input('search_desde'))
                        <a href="{{ route('estudios.index') }}" class="btn btn-secondary">×</a>
                    @endif

                    <!-- Filtro "Hasta" -->
                    <div class="input-group-prepend">
                        <span class="input-group-text">Hasta</span>
                    </div>
                    <input type="date" name="search_hasta" value="{{ request()->input('search_hasta') }}" class="form-control col-3"
                        placeholder="Buscar por fecha de fin">

                    @if (request()->input('search_hasta'))
                        <a href="{{ route('estudios.index') }}" class="btn btn-secondary">×</a>
                    @endif

                    <!-- Botones de Buscar y Exportar -->
                    <button style="margin-left: 5px;" class="btn btn-primary rounded" type="submit">Buscar</button>
                    <button type="button" class="btn btn-success ml-auto rounded" onclick="exportarExcel()">
                        <i class="fas fa-file-excel"></i> Exportar
                    </button>
                </div>





                <!-- Botones de acción -->



            </form>


            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif


            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
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
                            <tr id="estudio_{{ $estudio->nro_servicio }}" data-nro_servicio="{{ $estudio->nro_servicio }}">
                                <td>{{ $estudio->nro_servicio }}</td>
                                <td class="servicio">{{ $estudio->servicio }}</td>
                                <td>{{ $estudio->tipo_estudio }}</td>
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
                                    <a href="{{ route('estudios.edit', ['nro_servicio' => $estudio->nro_servicio, 'page' => request('page')]) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endif

                                    @if($estudio->estado !== 'creado' && $estudio->estado !== 'informando')
                                        <a href="{{ route('exportar.datos', $estudio->nro_servicio) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif

                                    @if (($estudio->estado !== 'creado' && $estudio->estado !== 'informando') &&
                                        (in_array('admin', $roles) || in_array('administrativo', $roles)))
                                        <a href="{{ route('enviar.datos', ['nro_servicio' => $estudio->nro_servicio, 'page' => request('page')]) }}" 
                                            class="btn btn-secondary btn-sm {{ $estudio->enviado == 1 ? 'disabled' : '' }}" 
                                            {{ $estudio->enviado == 1 ? 'aria-disabled=true' : '' }}>
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                    @endif
                                    
                                    @if ($estudio->estado == 'creado')
                                        @if (in_array('admin', $roles) || in_array('administrativo', $roles))
                                        <a href="{{ route('estudios.modify', ['nro_servicio' => $estudio->nro_servicio, 'page' => request('page')]) }}" class="btn btn-success btn-sm">
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
            

            {{ $estudios->appends([
                'search_nro_servicio' => request()->input('search_nro_servicio'),
                'search_servicio' => request()->input('search_servicio'),
                'search_tipo_estudio' => request()->input('search_tipo_estudio'),
                'search_estado' => request()->input('search_estado'),
                'search_paciente' => request()->input('search_paciente'),
                'search_profesional' => request()->input('search_profesional'),
                'search_desde' => request()->input('search_desde'),
                'search_hasta' => request()->input('search_hasta'),
                'search_obra_social' => request()->input('search_obra_social'),
                'page' => request()->input('page')
            ])->links() }}

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

<script>
    function exportarExcel() {
        var searchTipoEstudio = document.querySelector('input[name="search_tipo_estudio"]').value;
        var searchNroServicio = document.querySelector('input[name="search_nro_servicio"]').value;
        var searchServicio = document.querySelector('input[name="search_servicio"]').value;
        var searchEstado = document.querySelector('input[name="search_estado"]').value;
        var searchProfesional = document.querySelector('input[name="search_profesional"]').value;
        var searchPaciente = document.querySelector('input[name="search_paciente"]').value;
        var searchDesde = document.querySelector('input[name="search_desde"]').value;
        var searchHasta = document.querySelector('input[name="search_hasta"]').value;
        var searchObraSocial = document.querySelector('input[name="search_obra_social"]').value;

        var url = "{{ route('exportar.estudios') }}" +
            "?search_tipo_estudio=" + encodeURIComponent(searchTipoEstudio) +
            "&search_nro_servicio=" + encodeURIComponent(searchNroServicio) +
            "&search_servicio=" + encodeURIComponent(searchServicio) +
            "&search_estado=" + encodeURIComponent(searchEstado) +
            "&search_profesional=" + encodeURIComponent(searchProfesional) +
            "&search_paciente=" + encodeURIComponent(searchPaciente) +
            "&search_desde=" + encodeURIComponent(searchDesde) +
            "&search_hasta=" + encodeURIComponent(searchHasta) +
            "&search_obra_social=" + encodeURIComponent(searchObraSocial);

        window.location.href = url;
    }



    document.addEventListener('DOMContentLoaded', function() {
        const finalizado = @json(request('finalizado'));
        if(finalizado) {
            const row = document.querySelector(`#estudio_${finalizado}`);
            if (row) {
                // Aplicar un estilo temporal
                row.style.backgroundColor = '#ffeb3b'; // Cambia a un color de fondo destacado

                // Después de 5 segundos, volver al estilo normal
                setTimeout(() => {
                    row.style.backgroundColor = '';
                }, 20000);
            }
        }
    });
</script>
