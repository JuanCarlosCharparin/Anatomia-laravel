
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
        <form action="{{ route('estudios.update', $estudio->nro_servicio) }}" method="POST">
            @csrf
            @method('PUT')

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
                @if(!empty($estudio->tecnicas))
                    <textarea id="tecnicas" name="tecnicas" class="form-control">{{ $estudio->tecnicas }}</textarea>
                @else
                    <input type="text" class="form-control" id="tecnicas" name="tecnicas">
                @endif
                <p></p>
            </div>

            <div class="form-group">
                <label for="macro">Macro:</label>
                @if(!empty($estudio->macro))
                    <textarea id="macro" name="macro" class="form-control">{{ $estudio->macro }}</textarea>
                @else
                    <textarea id="macro" name="macro" class="form-control"></textarea>
                @endif
                <p></p>
            </div>
            
            <div class="form-group">
                <label for="fecha_macro">Fecha Macro:</label>
                @if(!empty($estudio->fecha_macro))
                    <input type="date" id="fecha_macro" name="fecha_macro" class="form-control" value="{{ $estudio->fecha_macro }}">
                @else
                    <input type="date" id="fecha_macro" name="fecha_macro" class="form-control">
                @endif
                <p></p>
            </div>
            
            <div class="form-group">
                <label for="micro">Micro:</label>
                @if(!empty($estudio->micro))
                    <textarea id="micro" name="micro" class="form-control">{{ $estudio->micro }}</textarea>
                @else
                    <textarea id="micro" name="micro" class="form-control"></textarea>
                @endif
                <p></p>
            </div>

            <div class="form-group">
                <label for="fecha_inclusion">Fecha Inclusión:</label>
                @if(!empty($estudio->fecha_inclusion))
                    <input type="date" id="fecha_inclusion" name="fecha_inclusion" class="form-control" value="{{ $estudio->fecha_inclusion }}">
                @else
                    <input type="date" id="fecha_inclusion" name="fecha_inclusion" class="form-control">
                @endif
                <p></p>
            </div>
            
            <div class="form-group">
                <label for="fecha_corte">Fecha Corte:</label>
                @if(!empty($estudio->fecha_corte))
                    <input type="date" id="fecha_corte" name="fecha_corte" class="form-control" value="{{ $estudio->fecha_corte }}">
                @else
                    <input type="date" id="fecha_corte" name="fecha_corte" class="form-control">
                @endif
                <p></p>
            </div>
            
            <div class="form-group">
                <label for="fecha_entrega">Fecha Entrega:</label>
                @if(!empty($estudio->fecha_entrega))
                    <input type="date" id="fecha_entrega" name="fecha_entrega" class="form-control" value="{{ $estudio->fecha_entrega }}">
                @else
                    <input type="date" id="fecha_entrega" name="fecha_entrega" class="form-control">
                @endif
                <p></p>
            </div>

            <div class="form-group">
                <label for="observacion">Notas:</label>
                @if(!empty($estudio->observacion))
                    <textarea id="observacion" name="observacion" class="form-control">{{ $estudio->observacion }}</textarea>
                @else
                    <textarea id="observacion" name="observacion" class="form-control"></textarea>
                @endif
                <p></p>
            </div>

            <div class="form-group">
                <label for="maligno">Maligno:</label>
                <select id="maligno" name="maligno" class="form-control">
                    <option value="">Selecciona una opción</option>
                    <option value="SI" {{ $estudio->maligno == 'opcion1' ? 'selected' : '' }}>SI</option>
                    <option value="NO" {{ $estudio->maligno == 'opcion2' ? 'selected' : '' }}>NO</option>
                    <option value="Indeterminado" {{ $estudio->maligno == 'opcion3' ? 'selected' : '' }}>Indeterminado</option>
                </select>
                <p></p>
            </div>

            <div class="form-group">
                <label for="observacion_interna">Observación Interna:</label>
                @if(!empty($estudio->observacion_interna))
                    <textarea id="observacion_interna" name="observacion_interna" class="form-control">{{ $estudio->observacion_interna }}</textarea>
                @else
                    <input type="text" class="form-control" id="observacion_interna" name="observacion_interna">
                @endif
                <p></p>
            </div>

            <div class="form-group">
                <label for="diagnostico_presuntivo">Diagnóstico:</label>
                @if(!empty($estudio->diagnostico_presuntivo))
                    <textarea id="diagnostico_presuntivo" name="diagnostico_presuntivo" class="form-control">{{ $estudio->diagnostico_presuntivo }}</textarea>
                @else
                    <input type="text" class="form-control" id="diagnostico_presuntivo" name="diagnostico_presuntivo">
                @endif
                <p></p>
            </div>
            <p></p>

            <hr>
            <p></p>
            <div class="form-group">
                <label for="recibe">Recibe:</label>
                @if(!empty($estudio->recibe))
                    <textarea id="recibe" name="recibe" class="form-control">{{ $estudio->recibe }}</textarea>
                @else
                    <input type="text" class="form-control" id="recibe" name="recibe">
                @endif
                <p></p>
            </div>

            <div class="form-group">
                <label for="tacos">Tacos:</label>
                @if(!empty($estudio->tacos))
                    <textarea id="tacos" name="tacos" class="form-control">{{ $estudio->tacos }}</textarea>
                @else
                <input type="text" class="form-control" id="tacos" name="tacos">
                @endif
                <p></p>
            </div>

    
            <button type="submit" class="btn btn-primary">Actualizar</button>

            
        
            
            <p></p>
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
    </script>
</x-app-layout>
