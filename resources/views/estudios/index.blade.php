<x-app-layout>
    <x-slot name="title">Estudios</x-slot>

    <div class="container mt-4">
        <h1>Estudios</h1>
        
        <form method="GET" action="{{ route('estudios.index') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search_general" value="{{ $searchGeneral }}" class="form-control" placeholder="Buscar general">
                <button class="btn btn-primary" type="submit">Buscar</button>

                @if($searchGeneral)
                    <a href="{{ route('estudios.index') }}" class="btn btn-secondary">×</a>
                @endif

                <input type="text" name="search_nro_servicio" value="{{ $searchNroServicio }}" class="form-control" placeholder="Buscar por N° Servicio">
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

        <table class="table">
            <thead>
                <tr>
                    <th>#</th> <!-- Contador -->
                    <th style="width: 10%;">N° Servicio</th>
                    <th style="width: 15%;">Servicio</th>
                    <th style="width: 20%;">Tipo de Estudio</th>
                    <th style="width: 10%;">Estado</th>
                    <th style="width: 25%;">Paciente</th>
                    <th style="width: 15%;">Obra Social</th>
                    <th style="width: 25%;">Diagnóstico</th>
                    <th style="width: 30%;">Fecha de Carga</th> <!-- Ancho aumentado aquí -->
                    <th style="width: 20%;">Profesional</th>
                    <th style="width: 20%;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($estudios as $estudio)
                    <tr>
                        <td>{{ $loop->iteration + $estudios->perPage() * ($estudios->currentPage() - 1) }}</td> <!-- Contador -->
                        <td>{{ $estudio->nro_servicio }}</td>
                        <td>{{ $estudio->servicio }}</td>
                        <td>{{ $estudio->tipo_estudio }}</td>
                        <td>{{ $estudio->estado }}</td>
                        <td>{{ $estudio->paciente }}</td>
                        <td>{{ $estudio->obra_social }}</td>
                        <td>{{ $estudio->diagnostico }}</td>
                        <td>{{ \Carbon\Carbon::parse($estudio->fecha_carga)->format('d-m-Y') }}</td>
                        <td>{{ $estudio->profesional }}</td>
                        <td>
                            <!-- Botón de Editar -->
                            <a href="{{ route('estudios.edit', $estudio->nro_servicio) }}" class="btn btn-warning btn-sm">Editar</a>
        
                            <!-- Formulario de Eliminar -->
                            <form action="#" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar este estudio?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Enlaces de Paginación -->
        {{ $estudios->appends(['search_general' => $searchGeneral, 'search_nro_servicio' => $searchNroServicio])->links() }}
    </div>
</x-app-layout>
