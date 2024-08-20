<head>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<x-app-layout>
    <x-slot name="title">Editar Estudio</x-slot>
    @section('title', 'Anatomía-Patológica')
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <div class="mt-4">
        <h1>Editar Estudio</h1>
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <p></p>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form id="estudioForm" action="{{ route('estudios.update', $estudio->nro_servicio) }}" method="POST">
            @csrf

            <style>
                .form-group {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 15px;
                }
                .form-group label {
                    flex-basis: 30%; /* Ancho de las etiquetas */
                    font-weight: bold;
                }
                .form-group strong {
                    flex-basis: 155%; /* Ancho de los valores */
                }
                hr {
                    margin: 10px 0; /* Espaciado entre los campos */
                }
                .form-group ul {
                    margin: 0;
                    padding: 0;
                    list-style: none;
                }
                .form-group ul li {
                    margin-bottom: 5px;
                }
                #ampliarInforme {
                    display: none; /* Inicialmente oculto */
                }
            </style>
    
            <!-- Campos comunes -->
            <div class="form-group">
                <label for="">N° Servicio: </label>
                <strong>{{ $estudio->nro_servicio }}</strong>
            </div>
            <hr>

            <div class="form-group">
                <label for="">Tipo de Estudio: </label>
                <strong>{{ $estudio->tipo_estudio }}</strong>
            </div>

            <div class="form-group">
                <label for="">Servicio: </label>
                <strong>{{ $estudio->servicio }}</strong>
            </div>
            
            <div class="form-group">
                <label for="">Fecha de Carga: </label>
                <strong>{{ \Carbon\Carbon::parse($estudio->fecha_carga)->format('d-m-Y') }}</strong>
            </div>
            
            <div class="form-group">
                <label for="">Informado por: </label>
                <strong>{{ $estudio->profesional }}</strong>
            </div>
            
            <div class="form-group">
                <label for="">Paciente: </label>
                <strong>{{ $estudio->paciente }}</strong>
            </div>
            
            <div class="form-group">
                <label for="">Obra Social: </label>
                <strong>{{ $estudio->obra_social }}</strong>
            </div>

            <div class="form-group">
                <label for="">Fecha Nacimiento: </label>
                <strong>{{ $estudio->fecha_nacimiento }}</strong>
            </div>

            <div class="form-group">
                <label for="">Edad: </label>
                <strong>{{ $estudio->edad }}</strong>
            </div>

            <div class="form-group">
                <label for="">Genero: </label>
                <strong>{{ $estudio->genero }}</strong>
            </div>
            
            <div class="form-group">
                <label for="">Estado: </label>
                <strong>{{ $estudio->estado }}</strong>
            </div>

            <div class="form-group">
                <label for="">Medico solicitante: </label>
                <strong>{{ $estudio->medico }}</strong>
            </div>

            <div class="form-group">
                <label for="">Diagnostico Presuntivo: </label>
                <strong>{{ $estudio->diagnostico }}</strong>
            </div>

            <div class="form-group">
                <label for="">Material/es: </label>
                <div style="flex-basis: 155%;">
                    <ul>
                        @foreach($materiales as $material)
                            <li>{{ $material->material }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>


            <div class="form-group">
                <label for="">Codigo/s Nomenclador AP: </label>
                <div style="flex-basis: 155%;">
                    <ul>
                        @foreach($codigos as $codigo)
                            <li>{{ $codigo->codigo }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <hr>
            <p></p>

            <!-- Campos específicos para Detalle -->

            <div class="form-group">
                <label for="tecnicas">Técnicas:</label>
                @php
                    $isFinalized = $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado' || $estudio->estado === 'finalizado, entregado y ampliado' || $estudio->estado === 'finalizado, ampliado y entregado' || $estudio->estado === 'finalizado y ampliado';
                    $disabled = $isFinalized ? 'disabled' : '';
                    
                    // Decodificar el JSON en un array si aún no se ha hecho en el controlador
                    $tecnicasSeleccionadas = is_array($estudio->tecnicas) ? $estudio->tecnicas : explode(',', $estudio->tecnicas);
                @endphp

                <select class="select2" id="tecnicas" name="tecnicas[]" multiple="multiple" style="width: 100%;" {{ $disabled }}>
                    <option value="Inclusion en bloque de parafina-histoplast" {{ in_array('Inclusion en bloque de parafina-histoplast', $tecnicasSeleccionadas) ? 'selected' : '' }}>Inclusion en bloque de parafina-histoplast</option>
                    <option value="Tincion con hematoxilina-eosina" {{ in_array('Tincion con hematoxilina-eosina', $tecnicasSeleccionadas) ? 'selected' : '' }}>Tincion con hematoxilina-eosina</option>
                    <option value="Tincion 15 (BIOPUR)" {{ in_array('Tincion 15 (BIOPUR)', $tecnicasSeleccionadas) ? 'selected' : '' }}>Tincion 15 (BIOPUR)</option>
                    <option value="Tincion papanicolaou" {{ in_array('Tincion papanicolaou', $tecnicasSeleccionadas) ? 'selected' : '' }}>Tincion papanicolaou</option>
                    <option value="GIEMSA" {{ in_array('GIEMSA', $tecnicasSeleccionadas) ? 'selected' : '' }}>GIEMSA</option>
                    <option value="Acido peryodico de Schiff (PAS)" {{ in_array('Acido peryodico de Schiff (PAS)', $tecnicasSeleccionadas) ? 'selected' : '' }}>Acido peryodico de Schiff (PAS)</option>
                    <option value="Microcirugia de Mohs" {{ in_array('Microcirugia de Mohs', $tecnicasSeleccionadas) ? 'selected' : '' }}>Microcirugía de Mohs</option>
                    <option value="Otros" {{ in_array('Otros', $tecnicasSeleccionadas) ? 'selected' : '' }}>Otros</option>
                </select>
                <p></p>
            </div>

            <div class="form-group">
                <label for="macro">Macro:</label>
                @php
                    // Determina si el estado está finalizado o en un estado posterior
                    $isFinalized = $estudio->estado === 'finalizado' || 
                                   $estudio->estado === 'finalizado y entregado' || 
                                   $estudio->estado === 'finalizado, entregado y ampliado' || 
                                   $estudio->estado === 'finalizado, ampliado y entregado' || 
                                   $estudio->estado === 'finalizado y ampliado';
                    
                    // Determina si el usuario tiene el rol de técnico o administrador
                    $isTecnico = $roles->contains('tecnico');
                    $isAdmin = $roles->contains('admin');
                    
                    // El campo será deshabilitado solo si está finalizado
                    $disabled = $isFinalized ? 'disabled' : '';
                @endphp
                
                @if(!empty($estudio->macro))
                    <textarea id="macro" name="macro" class="form-control" {{ $disabled }}>{{ $estudio->macro }}</textarea>
                @else
                    <textarea id="macro" name="macro" class="form-control" {{ $disabled }}></textarea>
                @endif
                <p></p>
            </div>
            
            <div class="form-group">
                <label for="fecha_macro">Fecha Macro:</label>
                @php
                    // Determina si el estado está finalizado o en un estado posterior
                    $isFinalized = $estudio->estado === 'finalizado' || 
                                   $estudio->estado === 'finalizado y entregado' || 
                                   $estudio->estado === 'finalizado, entregado y ampliado' || 
                                   $estudio->estado === 'finalizado, ampliado y entregado' || 
                                   $estudio->estado === 'finalizado y ampliado';
                    
                    // El campo será deshabilitado solo si está finalizado
                    $disabled = $isFinalized ? 'disabled' : '';
                @endphp
                
                @if(!empty($estudio->fecha_macro))
                    <input type="date" id="fecha_macro" name="fecha_macro" class="form-control" value="{{ $estudio->fecha_macro }}" {{ $disabled }}>
                @else
                    <input type="date" id="fecha_macro" name="fecha_macro" class="form-control" {{ $disabled }}>
                @endif
                <p></p>
            </div>
            
            <div class="form-group">
                <label for="micro">Micro:</label>
                @php
                    $isFinalized = $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado' || $estudio->estado === 'finalizado, entregado y ampliado' || $estudio->estado === 'finalizado, ampliado y entregado' || $estudio->estado === 'finalizado y ampliado';
                    $disabled = $isFinalized ? 'disabled' : '';
                @endphp
                @if(!empty($estudio->micro))
                    <textarea id="micro" name="micro" class="form-control" {{ $disabled }}>{{ $estudio->micro }}</textarea>
                @else
                    <textarea id="micro" name="micro" class="form-control" {{ $disabled }}></textarea>
                @endif
                <p></p>
            </div>
            
            <div class="form-group">
                <label for="fecha_inclusion">Fecha Inclusión:</label>
                @php
                    $isFinalized = $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado' || $estudio->estado === 'finalizado, entregado y ampliado' || $estudio->estado === 'finalizado, ampliado y entregado' || $estudio->estado === 'finalizado y ampliado';
                    $disabled = $isFinalized ? 'disabled' : '';
                @endphp
                @if(!empty($estudio->fecha_inclusion))
                    <input type="date" id="fecha_inclusion" name="fecha_inclusion" class="form-control" value="{{ $estudio->fecha_inclusion }}" {{ $disabled }}>
                @else
                    <input type="date" id="fecha_inclusion" name="fecha_inclusion" class="form-control" {{ $disabled }}>
                @endif
                <p></p>
            </div>
            
            <div class="form-group">
                <label for="fecha_corte">Fecha Corte:</label>
                @php
                    $isFinalized = $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado' || $estudio->estado === 'finalizado, entregado y ampliado' || $estudio->estado === 'finalizado, ampliado y entregado' || $estudio->estado === 'finalizado y ampliado';
                    $disabled = $isFinalized ? 'disabled' : '';
                @endphp
                @if(!empty($estudio->fecha_corte))
                    <input type="date" id="fecha_corte" name="fecha_corte" class="form-control" value="{{ $estudio->fecha_corte }}" {{ $disabled }}>
                @else
                    <input type="date" id="fecha_corte" name="fecha_corte" class="form-control" {{ $disabled }}>
                @endif
                <p></p>
            </div>
            
            <div class="form-group">
                <label for="fecha_entrega">Fecha Entrega:</label>
                @php
                    $isFinalized = $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado' || $estudio->estado === 'finalizado, entregado y ampliado' || $estudio->estado === 'finalizado, ampliado y entregado' || $estudio->estado === 'finalizado y ampliado';
                    $disabled = $isFinalized ? 'disabled' : '';
                @endphp
                @if(!empty($estudio->fecha_entrega))
                    <input type="date" id="fecha_entrega" name="fecha_entrega" class="form-control" value="{{ $estudio->fecha_entrega }}" {{ $disabled }}>
                @else
                    <input type="date" id="fecha_entrega" name="fecha_entrega" class="form-control" {{ $disabled }}>
                @endif
                <p></p>
            </div>
            
            <div class="form-group">
                <label for="maligno">Maligno:</label>
                @php
                    // Determina si el estado está finalizado o en un estado posterior
                    $isFinalized = $estudio->estado === 'finalizado' || 
                                   $estudio->estado === 'finalizado y entregado' || 
                                   $estudio->estado === 'finalizado, entregado y ampliado' || 
                                   $estudio->estado === 'finalizado, ampliado y entregado' || 
                                   $estudio->estado === 'finalizado y ampliado';
                    
                    // Determina si el usuario tiene el rol de administrador
                    $isAdmin = $roles->contains('admin');
                    
                    // El campo será deshabilitado si está finalizado o el usuario no es admin
                    $disabled = $isFinalized || !$isAdmin ? 'disabled' : '';
                @endphp
                
                <select id="maligno" name="maligno" class="form-control" {{ $disabled }}>
                    <option value="">Selecciona una opción</option>
                    <option value="SI" {{ $estudio->maligno == 'SI' ? 'selected' : '' }}>SI</option>
                    <option value="NO" {{ $estudio->maligno == 'NO' ? 'selected' : '' }}>NO</option>
                    <option value="Indeterminado" {{ $estudio->maligno == 'Indeterminado' ? 'selected' : '' }}>Indeterminado</option>
                </select>
                <p></p>
            </div>
            
            <div class="form-group">
                <label for="observacion_interna">Observación Interna:</label>
                @php
                    // Determina si el estado está finalizado o en un estado posterior
                    $isFinalized = $estudio->estado === 'finalizado' || 
                                   $estudio->estado === 'finalizado y entregado' || 
                                   $estudio->estado === 'finalizado, entregado y ampliado' || 
                                   $estudio->estado === 'finalizado, ampliado y entregado' || 
                                   $estudio->estado === 'finalizado y ampliado';
                    
                    // Determina si el usuario tiene el rol de administrador
                    $isAdmin = $roles->contains('admin');
                    
                    // El campo será deshabilitado si está finalizado o el usuario no es admin
                    $disabled = $isFinalized || !$isAdmin ? 'disabled' : '';
                @endphp
                
                @if(!empty($estudio->observacion_interna))
                    <textarea id="observacion_interna" name="observacion_interna" class="form-control" {{ $disabled }}>{{ $estudio->observacion_interna }}</textarea>
                @else
                    <textarea id="observacion_interna" name="observacion_interna" class="form-control" {{ $disabled }}></textarea>
                @endif
                <p></p>
            </div>
            
            <div class="form-group">
                <label for="diagnostico_presuntivo">Diagnóstico:</label>
                @php
                    // Determina si el estado está finalizado o en un estado posterior
                    $isFinalized = $estudio->estado === 'finalizado' || 
                                   $estudio->estado === 'finalizado y entregado' || 
                                   $estudio->estado === 'finalizado, entregado y ampliado' || 
                                   $estudio->estado === 'finalizado, ampliado y entregado' || 
                                   $estudio->estado === 'finalizado y ampliado';
                    
                    // Determina si el usuario tiene el rol de administrador
                    $isAdmin = $roles->contains('admin');
                    
                    // El campo será deshabilitado si está finalizado o el usuario no es admin
                    $disabled = $isFinalized || !$isAdmin ? 'disabled' : '';
                @endphp
                
                @if(!empty($estudio->diagnostico_presuntivo))
                    <textarea id="diagnostico_presuntivo" name="diagnostico_presuntivo" class="form-control" {{ $disabled }}>{{ $estudio->diagnostico_presuntivo }}</textarea>
                @else
                    <textarea id="diagnostico_presuntivo" name="diagnostico_presuntivo" class="form-control" {{ $disabled }}></textarea>
                @endif
                <p></p>
            </div>

            <div class="form-group">
                <label for="observacion">Notas:</label>
                @php
                    // Determina si el estado está finalizado o en un estado posterior
                    $isFinalized = $estudio->estado === 'finalizado' || 
                                   $estudio->estado === 'finalizado y entregado' || 
                                   $estudio->estado === 'finalizado, entregado y ampliado' || 
                                   $estudio->estado === 'finalizado, ampliado y entregado' || 
                                   $estudio->estado === 'finalizado y ampliado';
                    
                    // Determina si el usuario tiene el rol de técnico o administrador
                    $isTecnico = $roles->contains('tecnico');
                    $isAdmin = $roles->contains('admin');
                    
                    // El campo será deshabilitado si está finalizado o el usuario es técnico
                    $disabled = $isFinalized || $isTecnico ? 'disabled' : '';
                    
                    // La clase `readonly` se añade si el usuario es técnico y el campo no está finalizado
                    $readonly = $isTecnico && !$isFinalized ? 'readonly' : '';
                @endphp
                
                @if(!empty($estudio->observacion))
                    <textarea id="observacion" name="observacion" class="form-control" {{ $disabled }} {{ $readonly }}>{{ $estudio->observacion }}</textarea>
                @else
                    <textarea id="observacion" name="observacion" class="form-control" {{ $disabled }} {{ $readonly }}></textarea>
                @endif
                <p></p>
            </div>

            <hr>
            <p></p>

            <button type="submit" class="btn btn-primary" {{ $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado' || $estudio->estado === 'finalizado, entregado y ampliado' || $estudio->estado === 'finalizado, ampliado y entregado' ? 'disabled' : '' }}>
                Actualizar
            </button>
            <button type="button" id="finalizarEstudio" class="btn btn-success" {{ $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado' || $estudio->estado === 'finalizado, entregado y ampliado' || $estudio->estado === 'finalizado, ampliado y entregado' ? 'disabled' : '' }} >
                Finalizar Estudio
            </button>
            <a href="{{ route('estudios.index', ['page' => $page]) }}" class="btn btn-secondary">Cancelar</a>

            <p></p>
            <hr>
        </form>

        <form action="{{ route('estudios.finalizar', $estudio->nro_servicio) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="recibe">Recibe:</label>
                @php
                    $isFinalized = $estudio->estado === 'creado' || $estudio->estado === 'informando' || $estudio->estado === 'finalizado y entregado' || $estudio->estado === 'finalizado, entregado y ampliado' || $estudio->estado === 'finalizado, ampliado y entregado';
                    $disabled = $isFinalized ? 'disabled' : '';
                @endphp
                @if(!empty($estudio->recibe))
                    <textarea id="recibe" name="recibe" class="form-control" {{ $disabled }}>{{ $estudio->recibe }}</textarea>
                @else
                    <input type="text" class="form-control" id="recibe" name="recibe" {{ $disabled }}>
                @endif
                <p></p>
            </div>
            
            <div class="form-group">
                <label for="tacos">Tacos:</label>
                @php
                    $isFinalized = $estudio->estado === 'creado' || $estudio->estado === 'informando' || $estudio->estado === 'finalizado y entregado' || $estudio->estado === 'finalizado, entregado y ampliado' || $estudio->estado === 'finalizado, ampliado y entregado';
                    $disabled = $isFinalized ? 'disabled' : '';
                @endphp
                @if(!empty($estudio->tacos))
                    <textarea id="tacos" name="tacos" class="form-control" {{ $disabled }}>{{ $estudio->tacos }}</textarea>
                @else
                    <input type="text" class="form-control" id="tacos" name="tacos" {{ $disabled }}>
                @endif
                <p></p>
            </div>
            <button type="submit" class="btn btn-primary" {{ $estudio->estado === 'creado' || $estudio->estado === 'informando' || $estudio->estado === 'finalizado y entregado' || $estudio->estado === 'finalizado, entregado y ampliado' || $estudio->estado === 'finalizado, ampliado y entregado' ? 'disabled' : ''  }} >Actualizar</button>

            <p></p>
            <hr>
        </form>

        <!-- Botón para ampliar informe -->
        <p></p>
        @php
            // Determina si el usuario tiene el rol de administrador
            $isAdmin = $roles->contains('admin');
        @endphp

        @if($isAdmin)
            <button id="btnAmpliar" class="btn btn-primary">Ampliar Informe</button>
        @endif
        <p></p>
        <!-- Formulario para ampliar informe -->
        <form id="ampliarInformeForm" method="POST" action="{{ route('estudios.ampliarInforme', $estudio->nro_servicio) }}" style="display: none;">
            @csrf
            @php
                $isFinalized = $estudio->estado === 'creado' || $estudio->estado === 'informando' || $estudio->estado === 'finalizado y ampliado' || $estudio->estado === 'finalizado, entregado y ampliado' || $estudio->estado === 'finalizado, ampliado y entregado';
                $disabled = $isFinalized ? 'disabled' : '';
            @endphp
            <label for="informe">Informe Adicional:</label>
            @if(!empty($estudio->ampliar_informe))
                <textarea id="informe" name="informe" class="form-control" rows="5" {{ $disabled }}>{{ $estudio->ampliar_informe }}></textarea>
            @else
                <textarea id="informe" name="informe" class="form-control" rows="5" {{ $disabled }}></textarea>
            @endif
            <p></p>
            <button type="submit" class="btn btn-primary mt-2" {{ $estudio->estado === 'creado' || $estudio->estado === 'informando' || $estudio->estado === 'finalizado y ampliado' || $estudio->estado === 'finalizado, entregado y ampliado' || $estudio->estado === 'finalizado, ampliado y entregado'  ? 'disabled' : '' }}>Enviar Ampliación</button>
        </form>

    </div>
    <p></p>
    <script>

        // Inicializar select2
        $('.select2').select2({
                closeOnSelect: false, // Permitir múltiples selecciones sin cerrar el menú
                dropdownParent: $('body'), // Ajustar según sea necesario
                width: 'resolve' // Ajustar al ancho del contenedor
            });

            // Mantener el menú Select2 abierto al seleccionar o deseleccionar
            $('.select2').on('select2:select select2:unselect', function (e) {
                $(this).select2('open'); // Mantener el menú abierto
            });
        
        //btn ampliar informe
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('btnAmpliar').addEventListener('click', function() {
                var ampliarInforme = document.getElementById('ampliarInformeForm');
                if (ampliarInforme.style.display === 'none' || ampliarInforme.style.display === '') {
                    ampliarInforme.style.display = 'block';
                } else {
                    ampliarInforme.style.display = 'none';
                }
            });
        });

        document.getElementById('finalizarEstudio').addEventListener('click', function(event) {
            event.preventDefault(); // Prevenir el comportamiento por defecto del botón

            // Cambia el action del formulario a la ruta de finalización
            document.getElementById('estudioForm').action = '{{ route('estudios.finally', $estudio->nro_servicio) }}';

            // Enviar el formulario
            document.getElementById('estudioForm').submit();
        });
    </script>
</x-app-layout>
