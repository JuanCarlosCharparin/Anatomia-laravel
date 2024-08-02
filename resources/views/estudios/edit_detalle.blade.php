
<x-app-layout>
    <x-slot name="title">Editar Estudio</x-slot>

    <div class="container mt-4">
        <h1>Editar Estudio</h1>
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
                <label for="">Estado: </label>
                <strong>{{ $estudio->estado }}</strong>
            </div>

            <div class="form-group">
                <label for="">Diagnostico: </label>
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
            <hr>
            <p></p>

            <!-- Campos específicos para Detalle -->

            <div class="form-group">
                <label for="tecnicas">Técnicas:</label>
                @php
                    $isFinalized = $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado';
                    $disabled = $isFinalized ? 'disabled' : '';
                @endphp
            
                @if(!empty($estudio->tecnicas))
                    <textarea id="tecnicas" name="tecnicas" class="form-control" {{ $disabled }}>{{ $estudio->tecnicas }}</textarea>
                @else
                    <input type="text" class="form-control" id="tecnicas" name="tecnicas" {{ $disabled }}>
                @endif
                <p></p>
            </div>

            <div class="form-group">
                <label for="macro">Macro:</label>
                @php
                    $isFinalized = $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado';
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
                    $isFinalized = $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado';
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
                    $isFinalized = $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado';
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
                    $isFinalized = $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado';
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
                    $isFinalized = $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado';
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
                    $isFinalized = $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado';
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
                <label for="observacion">Notas:</label>
                @php
                    $isFinalized = $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado';
                    $disabled = $isFinalized ? 'disabled' : '';
                @endphp
                @if(!empty($estudio->observacion))
                    <textarea id="observacion" name="observacion" class="form-control" {{ $disabled }}>{{ $estudio->observacion }}</textarea>
                @else
                    <textarea id="observacion" name="observacion" class="form-control" {{ $disabled }}></textarea>
                @endif
                <p></p>
            </div>
            
            <div class="form-group">
                <label for="maligno">Maligno:</label>
                @php
                    $isFinalized = $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado';
                    $disabled = $isFinalized ? 'disabled' : '';
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
                    $isFinalized = $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado';
                    $disabled = $isFinalized ? 'disabled' : '';
                @endphp
                @if(!empty($estudio->observacion_interna))
                    <textarea id="observacion_interna" name="observacion_interna" class="form-control" {{ $disabled }}>{{ $estudio->observacion_interna }}</textarea>
                @else
                    <input type="text" class="form-control" id="observacion_interna" name="observacion_interna" {{ $disabled }}>
                @endif
                <p></p>
            </div>
            
            <div class="form-group">
                <label for="diagnostico_presuntivo">Diagnóstico:</label>
                @php
                    $isFinalized = $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado';
                    $disabled = $isFinalized ? 'disabled' : '';
                @endphp
                @if(!empty($estudio->diagnostico_presuntivo))
                    <textarea id="diagnostico_presuntivo" name="diagnostico_presuntivo" class="form-control" {{ $disabled }}>{{ $estudio->diagnostico_presuntivo }}</textarea>
                @else
                    <input type="text" class="form-control" id="diagnostico_presuntivo" name="diagnostico_presuntivo" {{ $disabled }}>
                @endif
                <p></p>
            </div>

            <hr>
            <p></p>

            <button type="submit" class="btn btn-primary" {{ $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado' ? 'disabled' : '' }}>
                Actualizar
            </button>
            <button type="button" id="finalizarEstudio" class="btn btn-success" {{ $estudio->estado === 'finalizado' || $estudio->estado === 'finalizado y entregado' ? 'disabled' : '' }} >
                Finalizar Estudio
            </button>
            <a href="{{ route('estudios.index') }}" class="btn btn-secondary">Cancelar</a>

            <p></p>
            <hr>
        </form>

        <form action="{{ route('estudios.finalizar', $estudio->nro_servicio) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="recibe">Recibe:</label>
                @php
                    $isFinalized = $estudio->estado === 'finalizado y entregado';
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
                    $isFinalized = $estudio->estado === 'finalizado y entregado';
                    $disabled = $isFinalized ? 'disabled' : '';
                @endphp
                @if(!empty($estudio->tacos))
                    <textarea id="tacos" name="tacos" class="form-control" {{ $disabled }}>{{ $estudio->tacos }}</textarea>
                @else
                    <input type="text" class="form-control" id="tacos" name="tacos" {{ $disabled }}>
                @endif
                <p></p>
            </div>
            <button type="submit" class="btn btn-primary" {{ $estudio->estado === 'finalizado y entregado' ? 'disabled' : ''  }} >Actualizar</button>
        </form>
        <p></p>
        <hr>
        <!--Ampliacion de informe-->
        <button id="btnAmpliar" class="btn btn-primary">Ampliar Informe</button>
        <div id="ampliarInforme" class="mt-3">
            <label for="informe">Informe Adicional:</label>
            <textarea id="informe" name="informe" class="form-control" rows="5"></textarea>
        </div>
        

    </div>
    <p></p>
    <script>
        //btn ampliar informe
        document.getElementById('btnAmpliar').addEventListener('click', function() {
            var ampliarInforme = document.getElementById('ampliarInforme');
            if (ampliarInforme.style.display === 'none') {
                ampliarInforme.style.display = 'block';
            } else {
                ampliarInforme.style.display = 'none';
            }
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
