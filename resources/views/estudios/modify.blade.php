<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Modificar Estudio')</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Agrega tus otros estilos aquí -->
    <!-- Bootstrap CSS (si usas Bootstrap) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <x-app-layout>
        <x-slot name="title">Modificar Estudio</x-slot>
        @section('title', 'Anatomía-Patológica')
        <div class="mt-4">
            <h1>Modificar Estudio</h1>
            <p></p>
            @php
                $professionals = App\Models\Paciente::getProfessionals();
                $servicios = App\Models\Especialidad::getServicio();
            @endphp
            <form method="POST" action="{{ route('estudios.updateEstudio', $estudio->nro_servicio) }}">
                @csrf

                <div class="form-group">
                    <label for="">N° Servicio: </label>
                    <strong>{{ $estudio->nro_servicio }}</strong>
                </div>
                <hr>

                <div class="form-group">
                    <label for="">Fecha de Carga: </label>
                    <strong>{{ \Carbon\Carbon::parse($estudio->fecha_carga)->format('d-m-Y') }}</strong>
                </div>

                <div class="form-group">
                    <label for="">Estado: </label>
                    <strong>{{ $estudio->estado }}</strong>
                </div>

                <div class="form-group">
                    <label for="search-input">DNI: </label>
                    <div class="input-group">
                        <input type="text" id="search-input" name="documento" value="{{ $estudio->documento }}" class="form-control" placeholder="Ingrese DNI del paciente">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary" id="search-button">
                                Buscar
                            </button>
                        </div>
                    </div>
                </div>

                <div id="results"></div>

                <input type="text" id="selected-person" name="paciente" value="{{ $estudio->paciente }}" class="form-control" placeholder="Nombre del paciente seleccionado" readonly>

                <p></p>

                <div class="form-group">
                    <label for="tipo_estudio">Modificar tipo de estudio:</label>
                    <select id="tipo_estudio" name="tipo_estudio" class="form-control" value="{{$estudio->tipo_estudio}}">
                        <option value="1" {{ old('tipo_estudio', $estudio->tipo_estudio_id) == 1 ? 'selected' : '' }}>Biopsia</option>
                        <option value="2" {{ old('tipo_estudio', $estudio->tipo_estudio_id) == 2 ? 'selected' : '' }}>Citología</option>
                        <option value="3" {{ old('tipo_estudio', $estudio->tipo_estudio_id) == 3 ? 'selected' : '' }}>Pap</option>
                        <option value="4" {{ old('tipo_estudio', $estudio->tipo_estudio_id) == 4 ? 'selected' : '' }}>Intraoperatorio</option>
                    </select>
                </div>
                <p></p>

                <div class="form-group">
                    <label for="professional-select">Modificar Profesional:</label>
                    <select id="professional-select" name="profesional_salutte_id" class="form-control">
                        <!-- La opción vacía es para cuando no se selecciona nada -->
                        <option value="">Seleccionar Profesional</option>
                        
                        <!-- Itera sobre los profesionales disponibles -->
                        @foreach ($professionals as $professional)
                            <option value="{{ $professional->profesional_id }}"
                                {{ $professional->profesional_id == $estudio->profesional_salutte_id ? 'selected' : '' }}>
                                {{ $professional->nombres }} {{ $professional->apellidos }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="servicio-select">Modificar Servicio:</label>
                    <select id="servicio-select" name="servicio_salutte_id" class="form-control">
                        <option value="">Seleccione un servicio</option>
                        @foreach ($servicios as $servicio)
                            <option value="{{ $servicio->servicio_salutte_id }}" 
                                {{ old('servicio_salutte_id', $estudio->servicio_salutte_id) == $servicio->servicio_salutte_id ? 'selected' : '' }}>
                                {{ $servicio->nombre_servicio }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="diagnostico">Modificar Diagnostico presuntivo:</label>
                    <input type="text" id="diagnostico" value="{{$estudio->diagnostico}}" name="diagnostico" class="form-control">
                </div>
                <p></p>
    
                <div class="form-group">
                    <label for="solicitante">Modificar Profesional solicitante :</label>
                    <input type="text" id="medico_solicitante" value="{{$estudio->medico}}" name="medico_solicitante" class="form-control">
                </div>

                <div class="form-group">
                    <label for="material">Modificar Material remitido:</label>
                    <div id="material-container">
                        @foreach ($materiales as $material)
                            <div class="form-group input-group mb-3">
                                <input type="text" name="materiales[]" class="form-control" value="{{ $material }}" placeholder="Ingrese material">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary add-material" type="button">+</button>
                                    <button class="btn btn-outline-danger remove-material" type="button">-</button>
                                </div>
                            </div>
                        @endforeach
                        <div class="form-group input-group mb-3">
                            <input type="text" name="materiales[]" class="form-control" placeholder="Ingrese material">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary add-material" type="button">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="codigos">Modificar código nomenclador AP:</label>
                    <div id="codigo-container">
                        @foreach ($codigos as $codigo)
                            <div class="form-group input-group mb-3">
                                <input type="text" name="codigos[]" class="form-control" value="{{ $codigo }}" placeholder="Ingrese código">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary add-codigo" type="button">+</button>
                                    <button class="btn btn-outline-danger remove-codigo" type="button">-</button>
                                </div>
                            </div>
                        @endforeach
                        <div class="form-group input-group mb-3">
                            <input type="text" name="codigos[]" class="form-control" placeholder="Ingrese código">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary add-codigo" type="button">+</button>
                            </div>
                        </div>
                    </div>
                </div>


                <button type="submit" class="btn btn-primary">Actualizar Estudio</button>
            </form>
        </div>

        <!-- Scripts de Bootstrap y jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

        <script>
           $(document).ready(function() {
                $('#search-button').click(function(e) {
                    e.preventDefault();

                    var searchTerm = $('#search-input').val().trim();
                    if (!searchTerm) {
                        $('#results').html('<p>Por favor, ingrese un término de búsqueda.</p>');
                        return;
                    }

                    $.ajax({
                        url: '{{ route('estudios.searchPatient') }}',
                        method: 'GET',
                        data: { search: searchTerm },
                        success: function(data) {
                            var resultHtml = '';
                            if (data.length > 0) {
                                data.forEach(function(patient) {
                                    resultHtml += '<div>';
                                    resultHtml += '<p><strong>HC Electrónica:</strong> ' + patient.id + '</p>';
                                    resultHtml += '<p><strong>Nombre:</strong> ' + patient.nombres + ' ' + patient.apellidos + '</p>';
                                    resultHtml += '<p><strong>DNI:</strong> ' + patient.documento + '</p>';
                                    resultHtml += '<p><strong>Fecha de Nacimiento:</strong> ' + patient.fecha_nacimiento + '</p>';
                                    resultHtml += '<p><strong>Obra Social:</strong> ' + patient.obra_social + '</p>';
                                    resultHtml += '<button type="button" class="btn btn-primary select-button" data-name="' + patient.nombres + ' ' + patient.apellidos + '">Seleccionar</button>';
                                    resultHtml += '</div><hr>';
                                });
                            } else {
                                resultHtml = '<p>No se encontraron resultados.</p>';
                            }
                            $('#results').html(resultHtml);
                        },
                        error: function(xhr) {
                            var errorMessage = 'Error al buscar datos.';
                            if (xhr.status === 404) {
                                errorMessage = 'La ruta no fue encontrada.';
                            } else if (xhr.status === 500) {
                                errorMessage = 'Error interno del servidor.';
                            }
                            $('#results').html('<p>' + errorMessage + '</p>');
                        }
                    });
                });

                $('#results').on('click', '.select-button', function(e) {
                    e.preventDefault();

                    var selectedName = $(this).data('name');
                    $('#selected-person').val(selectedName);
                });

                $(document).on('click', '.add-codigo', function() {
                    var inputGroup = $(this).closest('.input-group').clone();
                    inputGroup.find('input').val(''); // Limpia el campo de texto
                    inputGroup.find('.add-codigo').removeClass('add-codigo btn-outline-secondary')
                        .addClass('remove-codigo btn-outline-danger').text('-');
                    $('#codigo-container').append(inputGroup);
                });

                $(document).on('click', '.remove-codigo', function() {
                    $(this).closest('.input-group').remove(); // Elimina el campo de texto
                });

                $(document).on('click', '.add-material', function() {
                    var inputGroup = $(this).closest('.input-group').clone();
                    inputGroup.find('input').val(''); // Limpia el campo de texto
                    inputGroup.find('.add-material').removeClass('add-material btn-outline-secondary')
                        .addClass('remove-material btn-outline-danger').text('-');
                    $('#material-container').append(inputGroup);
                });

                $(document).on('click', '.remove-material', function() {
                    $(this).closest('.input-group').remove(); // Elimina el campo de texto
                });
            });
        </script>
    </x-app-layout>
</body>
</html>