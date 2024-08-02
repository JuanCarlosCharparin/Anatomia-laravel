
<x-app-layout>
    <x-slot name="title">Editar Pap</x-slot>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div class="container mt-4">
        <h1>Editar Pap</h1>
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
                .form-group {display: flex;justify-content: space-between;margin-bottom: 15px;}
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
                <label for="">Profesional: </label>
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
                <label for="">Materiales: </label>
                <ul>
                    @foreach($materiales as $material)
                        <li>{{ $material->material }}</li>
                    @endforeach
                </ul>
            </div>
            <hr>
            <p></p>

            <!-- Campos específicos para PAP -->

            <!-- jQuery -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

            <!-- Select2 CSS -->
            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

            <!-- Select2 JS -->
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

            <div class="form-group">
                <label for="estado_especimen">Estado Especimen:</label>
                <select class="select2" id="estado_especimen" name="estado_especimen[]" multiple="multiple" style="width: 100%;">
                    @php
                        // Decodificar el JSON en un array si aún no se ha hecho en el controlador
                        $estadoEspecimen = is_array($estudio->estado_especimen) ? $estudio->estado_especimen : explode(',', $estudio->estado_especimen);
                    @endphp
                    <option value="Satisfactorio" {{ in_array('Satisfactorio', $estadoEspecimen) ? 'selected' : '' }}>Satisfactorio</option>
                    <option value="Menor por defecto de fijacion" {{ in_array('Menor por defecto de fijacion', $estadoEspecimen) ? 'selected' : '' }}>Menor de lo óptimo por defecto de fijación o desecación</option>
                    <option value="Menor por hemorrragia" {{ in_array('Menor por hemorrragia', $estadoEspecimen) ? 'selected' : '' }}>Menor de lo óptimo por hemorragia</option>
                    <option value="Menor por citolisis" {{ in_array('Menor por citolisis', $estadoEspecimen) ? 'selected' : '' }}>Menor de lo óptimo por citólisis</option>
                    <option value="Menor por inflamacion" {{ in_array('Menor por inflamacion', $estadoEspecimen) ? 'selected' : '' }}>Menor de lo óptimo por inflamación</option>
                    <option value="Insactifactorio por escasa celularidad" {{ in_array('Insactifactorio por escasa celularidad', $estadoEspecimen) ? 'selected' : '' }}>Insatisfactorio por escasa celularidad</option>
                    <option value="Insactifactorio por defecto de fijacion" {{ in_array('Insactifactorio por defecto de fijacion', $estadoEspecimen) ? 'selected' : '' }}>Insatisfactorio por defecto de fijación o desecación</option>
                    <option value="Insactifactorio por inflamacion" {{ in_array('Insactifactorio por inflamacion', $estadoEspecimen) ? 'selected' : '' }}>Insatisfactorio por inflamación</option>
                    <option value="Insactifactorio por hemorrragia" {{ in_array('Insactifactorio por hemorrragia', $estadoEspecimen) ? 'selected' : '' }}>Insatisfactorio por hemorragia</option>
                    <option value="Insactifactorio por citolisis" {{ in_array('Insactifactorio por citolisis', $estadoEspecimen) ? 'selected' : '' }}>Insatisfactorio por citólisis</option>
                    <option value="Insactifactorio por componente_endocervical" {{ in_array('Insactifactorio por componente_endocervical', $estadoEspecimen) ? 'selected' : '' }}>Insatisfactorio por sin componente endocervical</option>
                    <option value="Insactifactorio por otros" {{ in_array('Insactifactorio por otros', $estadoEspecimen) ? 'selected' : '' }}>Insatisfactorio por otros</option>
                    <option value="Sin componente endocervical" {{ in_array('Sin componente endocervical', $estadoEspecimen) ? 'selected' : '' }}>Sin componente endocervical</option>
                </select>
            </div>

            <div class="form-group">
                <label for="celulas_pavimentosas">Células Pavimentosas:</label>
                <select class="select2" id="celulas_pavimentosas" name="celulas_pavimentosas[]" multiple="multiple" style="width: 100%;">
                    @php
                        // Decodificar el JSON en un array si aún no se ha hecho en el controlador
                        $celulasPavimentosas = is_array($estudio->celulas_pavimentosas) ? $estudio->celulas_pavimentosas : explode(',', $estudio->celulas_pavimentosas);
                    @endphp
                    <option value="Superficiales" {{ in_array('Superficiales', $celulasPavimentosas) ? 'selected' : '' }}>Superficiales</option>
                    <option value="Intermedias" {{ in_array('Intermedias', $celulasPavimentosas) ? 'selected' : '' }}>Intermedias</option>
                    <option value="Parabasales" {{ in_array('Parabasales', $celulasPavimentosas) ? 'selected' : '' }}>Parabasales</option>
                    <option value="Basales" {{ in_array('Basales', $celulasPavimentosas) ? 'selected' : '' }}>Basales</option>
                </select>
            </div>

            <div class="form-group">
                <label for="celulas_cilindricas">Células Cilíndricas:</label>
                <select class="select2" id="celulas_cilindricas" name="celulas_cilindricas[]" multiple="multiple" style="width: 100%;">
                    @php
                        // Asegurarse de que $celulas_cilindricas sea siempre un array
                        $celulas_cilindricas = is_array($estudio->celulas_cilindricas) 
                                                ? $estudio->celulas_cilindricas 
                                                : explode(',', $estudio->celulas_cilindricas ?? '');
                    @endphp
            
                    <option value="Endocervicales conservadas" {{ in_array('Endocervicales conservadas', $celulas_cilindricas) ? 'selected' : '' }}>Endocervicales conservadas</option>
                    <option value="Endocervicales reactivas" {{ in_array('Endocervicales reactivas', $celulas_cilindricas) ? 'selected' : '' }}>Endocervicales reactivas</option>
                    <option value="Endocervicales no se observan" {{ in_array('Endocervicales no se observan', $celulas_cilindricas) ? 'selected' : '' }}>Endocervicales no se observan</option>
                    <option value="Endocervicales con anomalias nucleares" {{ in_array('Endocervicales con anomalias nucleares', $celulas_cilindricas) ? 'selected' : '' }}>Endocervicales con anomalias nucleares</option>
                    <option value="Endometriales presentes" {{ in_array('Endometriales presentes', $celulas_cilindricas) ? 'selected' : '' }}>Endometriales presentes</option>
                    <option value="Endometriales con anomalias nucleares" {{ in_array('Endometriales con anomalias nucleares', $celulas_cilindricas) ? 'selected' : '' }}>Endometriales con anomalias nucleares</option>
                </select>
                <p></p>
            </div>

            <div class="form-group">
                <label for="valor_hormonal">Valor Hormonal:</label>
                <select class="form-control" name="valor_hormonal" id="valor_hormonal">
                    <option value="" disabled {{ !isset($estudio->valor_hormonal) ? 'selected' : '' }}>Selecciona un valor</option>
                    <option value="Trofico" {{ (isset($estudio->valor_hormonal) && $estudio->valor_hormonal === 'Trofico') || old('valor_hormonal') === 'Trofico' ? 'selected' : '' }}>Trófico</option>
                    <option value="Atrofico" {{ (isset($estudio->valor_hormonal) && $estudio->valor_hormonal === 'Atrofico') || old('valor_hormonal') === 'Atrofico' ? 'selected' : '' }}>Atrófico</option>
                    <option value="Hipotrofico" {{ (isset($estudio->valor_hormonal) && $estudio->valor_hormonal === 'Hipotrofico') || old('valor_hormonal') === 'Hipotrofico' ? 'selected' : '' }}>Hipotrófico</option>
                    <option value="Trofismo disociado" {{ (isset($estudio->valor_hormonal) && $estudio->valor_hormonal === 'Trofismo disociado') || old('valor_hormonal') === 'Trofismo disociado' ? 'selected' : '' }}>Trofismo Disociado o Irregular</option>
                </select>
                <p></p>
            </div>

            <div class="form-group">
                <label for="fecha_lectura">Fecha Lectura:</label>
                <input type="date" class="form-control" id="fecha_lectura" name="fecha_lectura" 
                       value="{{ $estudio->fecha_lectura ? \Carbon\Carbon::parse($estudio->fecha_lectura)->format('Y-m-d') : '' }}">
            </div>

            <div class="form-group">
                <label for="valor_hormonal_HC">Valor Hormonal HC:</label>
                <select class="form-control" name="valor_hormonal_HC" id="valor_hormonal_HC">
                    <option value="" disabled {{ !isset($estudio->valor_hormonal) ? 'selected' : '' }}>Selecciona un valor</option>
                    <option value="SI" {{ $estudio->valor_hormonal_HC === 'SI' ? 'selected' : '' }}>Sí</option>
                    <option value="NO" {{ $estudio->valor_hormonal_HC === 'NO' ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div class="form-group">
                <label for="cambios_reactivos">Cambios Reactivos:</label>
                <select class="select2" id="cambios_reactivos" name="cambios_reactivos[]" multiple="multiple" style="width: 100%;">
                    @php
                        // Asegúrate de que $cambios_reactivos sea un array
                        $cambios_reactivos = is_array($estudio->cambios_reactivos) 
                                            ? $estudio->cambios_reactivos 
                                            : explode(',', $estudio->cambios_reactivos ?? '');
                    @endphp
                    
                    <option value="Asociados inflamacion_leve" {{ in_array('Asociados inflamacion_leve', $cambios_reactivos) ? 'selected' : '' }}>Asociados a inflamación leve</option>
                    <option value="Asociados inflamacion moderada" {{ in_array('Asociados inflamacion moderada', $cambios_reactivos) ? 'selected' : '' }}>Asociados a inflamación moderada</option>
                    <option value="Asociados inflamacion severa" {{ in_array('Asociados inflamacion severa', $cambios_reactivos) ? 'selected' : '' }}>Asociados a inflamación severa</option>
                    <option value="Trastornos madurativos" {{ in_array('Trastornos madurativos', $cambios_reactivos) ? 'selected' : '' }}>Trastornos madurativos</option>
                    <option value="Efecto radioterapia" {{ in_array('Efecto radioterapia', $cambios_reactivos) ? 'selected' : '' }}>Efecto de radioterapia</option>
                    <option value="DIU" {{ in_array('DIU', $cambios_reactivos) ? 'selected' : '' }}>DIU</option>
                    <option value="Terapias hormonales" {{ in_array('Terapias hormonales', $cambios_reactivos) ? 'selected' : '' }}>Terapias hormonales</option>
                    <option value="Otros" {{ in_array('Otros', $cambios_reactivos) ? 'selected' : '' }}>Otros</option>
                </select>
            </div>

            <div class="form-group">
                <label for="cambios_asoc_celula_pavimentosa">Cambios Asociados a Célula Pavimentosa:</label>
                <select class="select2" id="cambios_asoc_celula_pavimentosa" name="cambios_asoc_celula_pavimentosa[]" multiple="multiple" style="width: 100%;">
                    @php
                        // Asegúrate de que $cambios_asoc_celula_pavimentosa sea un array
                        $cambios_asoc_celula_pavimentosa = is_array($estudio->cambios_asoc_celula_pavimentosa) 
                                                         ? $estudio->cambios_asoc_celula_pavimentosa 
                                                         : explode(',', $estudio->cambios_asoc_celula_pavimentosa ?? '');
                    @endphp
                    
                    <option value="Escamas anucleadas" {{ in_array('Escamas anucleadas', $cambios_asoc_celula_pavimentosa) ? 'selected' : '' }}>Escamas anucleadas</option>
                    <option value="Paraqueratosis" {{ in_array('Paraqueratosis', $cambios_asoc_celula_pavimentosa) ? 'selected' : '' }}>Paraqueratosis</option>
                    <option value="Binucleacion" {{ in_array('Binucleacion', $cambios_asoc_celula_pavimentosa) ? 'selected' : '' }}>Binucleacion</option>
                    <option value="Megalocariosis" {{ in_array('Megalocariosis', $cambios_asoc_celula_pavimentosa) ? 'selected' : '' }}>Megalocariosis</option>
                    <option value="Hipercromasia" {{ in_array('Hipercromasia', $cambios_asoc_celula_pavimentosa) ? 'selected' : '' }}>Hipercromasia</option>
                    <option value="Coilocitos" {{ in_array('Coilocitos', $cambios_asoc_celula_pavimentosa) ? 'selected' : '' }}>Coilocitos</option>
                    <option value="Anisocariosis" {{ in_array('Anisocariosis', $cambios_asoc_celula_pavimentosa) ? 'selected' : '' }}>Anisocariosis</option>
                    <option value="Anfofilia" {{ in_array('Anfofilia', $cambios_asoc_celula_pavimentosa) ? 'selected' : '' }}>Anfofilia</option>
                    <option value="Aros perinucleares" {{ in_array('Aros perinucleares', $cambios_asoc_celula_pavimentosa) ? 'selected' : '' }}>Aros perinucleares</option>
                </select>
            </div>

            <div class="form-group">
                <label for="cambios_celula_glandulares">Anomalías en Células Glandulares:</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="cambios_celula_glandulares" 
                    name="cambios_celula_glandulares" 
                    value="{{ old('cambios_celula_glandulares', $estudio->cambios_celula_glandulares) }}"
                >
            </div>

            <div class="form-group">
                <label for="celula_metaplastica">Célula Metaplástica:</label>
                <select class="select2" id="celula_metaplastica" name="celula_metaplastica[]" multiple="multiple" style="width: 100%;">
                    @php
                        // Decodificar el valor de celula_metaplastica en un array
                        $celulaMetaplastica = is_array($estudio->celula_metaplastica) 
                            ? $estudio->celula_metaplastica 
                            : (empty($estudio->celula_metaplastica) ? [] : explode(',', $estudio->celula_metaplastica));
                    @endphp
                    <option value="Presentes" {{ in_array('Presentes', $celulaMetaplastica) ? 'selected' : '' }}>Presentes</option>
                    <option value="Semi maduras" {{ in_array('Semi maduras', $celulaMetaplastica) ? 'selected' : '' }}>Semi maduras</option>
                    <option value="Inmaduras" {{ in_array('Inmaduras', $celulaMetaplastica) ? 'selected' : '' }}>Inmaduras</option>
                    <option value="Megalocariosis_aspecto_reactivo" {{ in_array('Megalocariosis_aspecto_reactivo', $celulaMetaplastica) ? 'selected' : '' }}>Con megalocariosis de aspecto reactivo</option>
                    <option value="Megalocariosis" {{ in_array('Megalocariosis', $celulaMetaplastica) ? 'selected' : '' }}>Con megalocariosis</option>
                    <option value="Anisocariosis" {{ in_array('Anisocariosis', $celulaMetaplastica) ? 'selected' : '' }}>Con anisocariosis</option>
                    <option value="Hipercromasia" {{ in_array('Hipercromasia', $celulaMetaplastica) ? 'selected' : '' }}>Hipercromasia</option>
                </select>
            </div>

            <div class="form-group">
                <label for="otras_neo_malignas">Otras Neoplasias Malignas:</label>
                <input type="text" class="form-control" id="otras_neo_malignas" name="otras_neo_malignas" value="{{ old('otras_neo_malignas', $estudio->otras_neo_malignas ?? '') }}">
            </div>

            <div class="form-group">
                <label for="toma">Toma:</label>
                <select class="select2" id="toma" name="toma[]" multiple="multiple" style="width: 100%;">
                    @php
                        // Decodificar el JSON en un array si aún no se ha hecho en el controlador
                        $toma = is_array($estudio->toma) ? $estudio->toma : explode(',', $estudio->toma);
                    @endphp
                    <option value="Exo" {{ in_array('Exo', $toma) ? 'selected' : '' }}>Exo</option>
                    <option value="Endo" {{ in_array('Endo', $toma) ? 'selected' : '' }}>Endo</option>
                    <option value="Cupula" {{ in_array('Cupula', $toma) ? 'selected' : '' }}>Cúpula</option>
                </select>
            </div>

            <div class="form-group">
                <label for="recomendaciones">Recomendaciones:</label>
                <select class="select2" id="recomendaciones" name="recomendaciones[]" multiple="multiple" style="width: 100%;">
                    @php
                        // Decodificar el JSON en un array si aún no se ha hecho en el controlador
                        $recomendaciones = is_array($estudio->recomendaciones) ? $estudio->recomendaciones : explode(',', $estudio->recomendaciones);
                    @endphp
                    <option value="Tratar repetir" {{ in_array('Tratar repetir', $recomendaciones) ? 'selected' : '' }}>Tratar y repetir</option>
                    <option value="Estudio canal" {{ in_array('Estudio canal', $recomendaciones) ? 'selected' : '' }}>Estudio del canal</option>
                    <option value="Reevaluacion_colposcopica" {{ in_array('Reevaluacion_colposcopica', $recomendaciones) ? 'selected' : '' }}>Reevaluación colposcópica</option>
                    <option value="Biopsiar" {{ in_array('Biopsiar', $recomendaciones) ? 'selected' : '' }}>Biopsiar</option>
                    <option value="Control Anual" {{ in_array('Control Anual', $recomendaciones) ? 'selected' : '' }}>Control Anual</option>
                    <option value="Control Semestral" {{ in_array('Control Semestral', $recomendaciones) ? 'selected' : '' }}>Control Semestral</option>
                </select>
            </div>
            

            <div class="form-group">
                <label for="microorganismos">Microorganismos:</label>
                <select class="select2" id="microorganismos" name="microorganismos[]" multiple="multiple" style="width: 100%;">
                    @php
                        // Verificar si $estudio->microorganismos ya es un array, si no, convertir la cadena en un array
                        $microorganismos = is_array($estudio->microorganismos) ? $estudio->microorganismos : explode(',', $estudio->microorganismos);
                    @endphp
                    <option value="Hifas micoticas" {{ in_array('Hifas micóticas', $microorganismos) ? 'selected' : '' }}>Hifas micóticas</option>
                    <option value="Gardnerella" {{ in_array('Gardnerella', $microorganismos) ? 'selected' : '' }}>Gardnerella</option>
                    <option value="Actinomyces" {{ in_array('Actinomyces', $microorganismos) ? 'selected' : '' }}>Actinomyces</option>
                    <option value="Chlamydias" {{ in_array('Chlamydias', $microorganismos) ? 'selected' : '' }}>Chlamydias</option>
                    <option value="Cocobacilar" {{ in_array('Cocobacilar', $microorganismos) ? 'selected' : '' }}>Cocobacilar</option>
                    <option value="Bacilar" {{ in_array('Bacilar', $microorganismos) ? 'selected' : '' }}>Bacilar</option>
                    <option value="Trichomonas" {{ in_array('Trichomonas', $microorganismos) ? 'selected' : '' }}>Trichomonas</option>
                    <option value="Citomegalovirus" {{ in_array('Citomegalovirus', $microorganismos) ? 'selected' : '' }}>Citomegalovirus</option>
                    <option value="Herpes virus" {{ in_array('Herpes virus', $microorganismos) ? 'selected' : '' }}>Herpes Virus</option>
                    <option value="Cambios HPV" {{ in_array('Cambios HPV', $microorganismos) ? 'selected' : '' }}>Cambios por HPV</option>
                    <option value="Cocos" {{ in_array('Cocos', $microorganismos) ? 'selected' : '' }}>Cocos</option>
                    <option value="Bacilos Doderlein" {{ in_array('Bacilos Doderlein', $microorganismos) ? 'selected' : '' }}>Bacilos de Doderlein</option>
                    <option value="Otros" {{ in_array('Otros', $microorganismos) ? 'selected' : '' }}>Otros</option>
                </select>
            </div>

            <div class="form-group">
                <label for="resultado">Resultado:</label>
                <select class="select2" id="resultado" name="resultado[]" multiple="multiple" style="width: 100%;">
                    @php
                        // Verificar si $estudio->resultado ya es un array, si no, convertir la cadena en un array
                        $resultado = is_array($estudio->resultado) ? $estudio->resultado : explode(',', $estudio->resultado);
                    @endphp
                    <option value="Insactifactorio" {{ in_array('Insactifactorio', $resultado) ? 'selected' : '' }}>Insactifactorio</option>
                    <option value="NEGATIVO" {{ in_array('NEGATIVO', $resultado) ? 'selected' : '' }}>NEGATIVO</option>
                    <option value="Anormalidad celulas epiteliales" {{ in_array('Anormalidad celulas epiteliales', $resultado) ? 'selected' : '' }}>Anormalidad de células epiteliales</option>
                    <option value="NEGATIVO LESION INTRAEPITELIAL" {{ in_array('NEGATIVO LESION INTRAEPITELIAL', $resultado) ? 'selected' : '' }}>NEGATIVO PARA LESIÓN INTRAEPITELIAL O MALIGNIDAD</option>
                    <option value="ASC-US" {{ in_array('ASC-US', $resultado) ? 'selected' : '' }}>ASC-US</option>
                    <option value="ASC-H" {{ in_array('ASC-H', $resultado) ? 'selected' : '' }}>ASC-H</option>
                    <option value="L-SIL" {{ in_array('L-SIL', $resultado) ? 'selected' : '' }}>LSIL</option>
                    <option value="HSIL" {{ in_array('HSIL', $resultado) ? 'selected' : '' }}>HSIL</option>
                </select>
            </div>
            
        
    
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <p></p>
        </form>
        

    </div>
    <p></p>
    
    <script>
        $(document).ready(function() {
            // Inicializar Select2
            $('.select2').select2({
                closeOnSelect: false, // Permitir múltiples selecciones sin cerrar el menú
                dropdownParent: $('body'), // Ajustar según sea necesario
                width: 'resolve' // Ajustar al ancho del contenedor
            });

            // Actualizar la selección de Select2
            $('.select2').on('select2:select select2:unselect', function (e) {
                $(this).select2('open'); // Mantener el menú abierto
            });

            // Enviar los datos como una cadena separada por comas al enviar el formulario
            $('#myForm').on('submit', function(e) {
                e.preventDefault(); // Prevenir el envío del formulario para prueba

                // Obtener la cadena de texto separada por comas para cada select2
                $('.select2').each(function() {
                    var selectedValues = $(this).select2('data').map(function(option) {
                        return option.text;
                    }).join(',');
                    console.log('Selected values for ' + $(this).attr('id') + ': ' + selectedValues);
                });

                // Aquí puedes enviar los datos al controlador usando AJAX
            });
        });

    </script>

</x-app-layout>