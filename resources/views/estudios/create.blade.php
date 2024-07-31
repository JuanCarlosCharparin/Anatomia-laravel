<x-app-layout>
    <x-slot name="title">Crear Estudio</x-slot>

    <div class="container mt-4">
        <h1>Crear Estudio</h1>
        @php
            /*$professionals = App\Models\Paciente::getProfessionals();*/
        @endphp
        <form method="POST" action="{{ route('estudios.store') }}">
            @csrf

            <div class="form-group">
                <label for="nro_servicio">N° Servicio: </label>
                <!-- Mostrar el último número de servicio -->
                <input type="text" name="nro_servicio" id="nro_servicio" class="form-control" value="{{ $newServicioNumber }}" readonly>
            </div>
            <p></p>

            <div class="form-group">
                <label for="fecha">Ingrese la fecha: </label>
                <div id="date-container">
                    <div class="form-group input-group mb-3">
                        <input type="date" name="fecha_carga" id="fecha_carga" class="form-control" placeholder="Ingrese la fecha">
                    </div>
                </div>
            </div>
            <p></p>
            
            <div class="form-group">
                <label for="search-input">DNI o Nombre: </label>
                <div class="input-group">
                    <input type="text" id="search-input" name="documento" class="form-control" placeholder="Ingrese DNI o nombre">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-primary" id="search-button">
                            Buscar
                        </button>
                    </div>
                </div>
            </div>
            <p></p>

            <div id="results"></div>
            <p></p>
            <input type="text" id="selected-person" name="paciente" class="form-control" placeholder="Nombre del paciente seleccionado">
            <p></p>
            

            <!-- Otros campos del formulario -->
            <div class="form-group">
                <label for="professional-select">Seleccionar Profesional: </label>
    
                <select id="professional-select" name="profesional_salutte_id" class="form-control">
                    <option value="">Seleccione un profesional</option>
                    @foreach ($professionals as $professional)
                        <option value="{{ $professional->profesional_id }}">
                            {{ $professional->nombres }} {{ $professional->apellidos }}
                        </option>
                    @endforeach
                </select>
            </div>
            <p></p>

            <div class="form-group">
                <label for="servicio-select">Seleccionar Servicio:</label>
                <select id="servicio-select" name="servicio_salutte_id" class="form-control">
                    <option value="">Seleccione un servicio</option>
                    @foreach ($servicios as $servicio)
                        <option value="{{ $servicio->servicio_salutte_id }}" {{ old('servicio_salutte_id') == $servicio->servicio_salutte_id ? 'selected' : '' }}>
                            {{ $servicio->nombre_servicio }}
                        </option>
                    @endforeach
                </select>
            </div>
            <p></p>

            <div class="form-group">
                <label for="tipo_estudio">Seleccione un tipo de estudio:</label>
                <select id="tipo_estudio" name="tipo_estudio" class="form-control" required>
                    <option value="1" {{ old('tipo_estudio') == 1 ? 'selected' : '' }}>Biopsia</option>
                    <option value="2" {{ old('tipo_estudio') == 2 ? 'selected' : '' }}>Citología</option>
                    <option value="3" {{ old('tipo_estudio') == 3 ? 'selected' : '' }}>Pap</option>
                    <option value="4" {{ old('tipo_estudio') == 4 ? 'selected' : '' }}>Intraoperatorio</option>
                </select>
            </div>
            <p></p>


            <div class="form-group">
                <label for="material">Material remitido:</label>
                <div id="material-container">
                    <div class="form-group input-group mb-3">
                        <input type="text" name="materiales[]" class="form-control" placeholder="Ingrese material">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary add-material" type="button">+</button>
                        </div>
                    </div>
                </div>
            </div>
            <p></p>

            <div class="form-group">
                <label for="diagnostico">Diagnostico presuntivo:</label>
                <input type="text" id="diagnostico" name="diagnostico" class="form-control">
            </div>
            <p></p>

            <div class="form-group">
                <label for="solicitante">Profesional solicitante :</label>
                <input type="text" id="medico_solicitante" name="medico_solicitante" class="form-control">
            </div>
            <p></p>

            <div class="form-group">
                <label for="codigos">Ingrese código nomenclador AP:</label>
                <div id="input-container">
                    <div class="form-group input-group mb-3">
                        <input type="text" name="codigos[]" class="form-control" placeholder="Ingrese código">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary add-input" type="button">+</button>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <button type="submit" class="btn btn-primary">Crear Estudio</button>
            <p></p>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
       $(document).ready(function() {
            $('#search-button').click(function(e) {
                e.preventDefault(); // Prevenir el comportamiento predeterminado del botón

                var searchTerm = $('#search-input').val().trim(); // Obtener y recortar espacios en blanco del término de búsqueda
                if (!searchTerm) {
                    $('#results').html('<p>Por favor, ingrese un término de búsqueda.</p>'); // Mensaje si el campo de búsqueda está vacío
                    return;
                }

                console.log('Buscando:', searchTerm); // Depuración: Verificar que el término de búsqueda se está enviando

                $.ajax({
                    url: '{{ route('estudios.searchPatient') }}',
                    method: 'GET',
                    data: { search: searchTerm },
                    success: function(data) {
                        console.log('Datos recibidos:', data); // Depuración: Verificar que se reciben datos

                        var resultHtml = '';
                        if (data.length > 0) {
                            data.forEach(function(patient) {
                                resultHtml += '<div>';
                                resultHtml += '<p><strong>ID:</strong> ' + patient.id + '</p>';
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
                        $('#results').html(resultHtml); // Mostrar resultados en el contenedor
                    },
                    error: function(xhr, status, error) {
                        console.log('Error de AJAX:', {
                            status: status,
                            error: error,
                            responseText: xhr.responseText
                        }); // Depuración: Verificar error

                        // Mostrar un mensaje de error más detallado
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

            // Manejar el clic en el botón "Seleccionar"
            $('#results').on('click', '.select-button', function(e) {
                e.preventDefault(); // Prevenir el comportamiento predeterminado del botón

                var selectedName = $(this).data('name');
                $('#selected-person').val(selectedName); // Asignar el nombre al campo de entrada
            });

            //Agregar mas campos en codigos

            $(document).ready(function() {
                $(document).on('click', '.add-input', function() {
                    var inputGroup = $(this).closest('.input-group').clone();
                    inputGroup.find('input').val('');
                    inputGroup.find('.add-input').removeClass('add-input btn-outline-secondary')
                        .addClass('remove-input btn-outline-danger').text('-');
                    $('#input-container').append(inputGroup);
                });

                $(document).on('click', '.remove-input', function() {
                    $(this).closest('.input-group').remove();
                });
            });

            //Agregar mas campos en material

            $(document).on('click', '.add-material', function() {
                var inputGroup = $(this).closest('.input-group').clone();
                inputGroup.find('input').val('');
                inputGroup.find('.add-material').removeClass('add-material btn-outline-secondary')
                    .addClass('remove-material btn-outline-danger').text('-');
                $('#material-container').append(inputGroup);
            });

            $(document).on('click', '.remove-material', function() {
                $(this).closest('.input-group').remove();
            });
        });
    </script>
</x-app-layout>
