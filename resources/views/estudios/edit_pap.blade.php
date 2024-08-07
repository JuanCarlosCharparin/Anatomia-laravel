<head>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<x-app-layout>
    <x-slot name="title">Editar Pap</x-slot>
    @section('title', 'Anatomía-Patológica')
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div class="mt-4">
        <h1>Editar Pap</h1>
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
            </style>
    
            <!-- Campos comunes -->
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
            <hr>
            <p></p>

            <!-- Campos específicos para PAP -->

            <!-- Select2 CSS -->
            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

            <!-- Select2 JS -->
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

            <div class="form-group">
                <label for="estado_especimen">Estado Especimen:</label>
                @php
                    // Decodificar el JSON en un array si aún no se ha hecho en el controlador
                    $estadoEspecimen = is_array($estudio->estado_especimen) ? $estudio->estado_especimen : explode(',', $estudio->estado_especimen);
            
                    // Verificar si el campo tiene datos para habilitar o deshabilitar el campo
                    $isFinalized = $estudio->estado === 'finalizado';
                    $disabled = $isFinalized || empty($estadoEspecimen) ? 'disabled' : '';
                @endphp
                
                <select class="select2" id="estado_especimen" name="estado_especimen[]" multiple="multiple" style="width: 100%;" {{ $disabled }}>
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
                    <option value="Insactifactorio por componente endocervical" {{ in_array('Insactifactorio por componente endocervical', $estadoEspecimen) ? 'selected' : '' }}>Insatisfactorio por sin componente endocervical</option>
                    <option value="Insactifactorio por otros" {{ in_array('Insactifactorio por otros', $estadoEspecimen) ? 'selected' : '' }}>Insatisfactorio por otros</option>
                    <option value="Sin componente endocervical" {{ in_array('Sin componente endocervical', $estadoEspecimen) ? 'selected' : '' }}>Sin componente endocervical</option>
                </select>
            </div>

            <div class="form-group">
                <label for="celulas_pavimentosas">Células Pavimentosas:</label>
                @php
                    // Decodificar el JSON en un array si aún no se ha hecho en el controlador
                    $celulasPavimentosas = is_array($estudio->celulas_pavimentosas) ? $estudio->celulas_pavimentosas : explode(',', $estudio->celulas_pavimentosas);
            
                    // Verificar si el campo tiene datos para habilitar o deshabilitar el campo
                    $isFinalized = $estudio->estado === 'finalizado';
                    $disabled = $isFinalized || empty($celulasPavimentosas) ? 'disabled' : '';
                @endphp
                
                <select class="select2" id="celulas_pavimentosas" name="celulas_pavimentosas[]" multiple="multiple" style="width: 100%;" {{ $disabled }}>
                    <option value="Superficiales" {{ in_array('Superficiales', $celulasPavimentosas) ? 'selected' : '' }}>Superficiales</option>
                    <option value="Intermedias" {{ in_array('Intermedias', $celulasPavimentosas) ? 'selected' : '' }}>Intermedias</option>
                    <option value="Parabasales" {{ in_array('Parabasales', $celulasPavimentosas) ? 'selected' : '' }}>Parabasales</option>
                    <option value="Basales" {{ in_array('Basales', $celulasPavimentosas) ? 'selected' : '' }}>Basales</option>
                </select>
            </div>

            <div class="form-group">
                <label for="celulas_cilindricas">Células Cilíndricas:</label>
                @php
                    // Asegurarse de que $celulas_cilindricas sea siempre un array
                    $celulas_cilindricas = is_array($estudio->celulas_cilindricas) 
                                            ? $estudio->celulas_cilindricas 
                                            : explode(',', $estudio->celulas_cilindricas ?? '');
                    
                    // Verificar si el campo tiene datos para habilitar o deshabilitar el campo
                    $isFinalized = $estudio->estado === 'finalizado';
                    $disabled = $isFinalized || empty($celulas_cilindricas) ? 'disabled' : '';
                @endphp
            
                <select class="select2" id="celulas_cilindricas" name="celulas_cilindricas[]" multiple="multiple" style="width: 100%;" {{ $disabled }}>
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
                @php
                    // Determinar si el campo debe estar deshabilitado
                    $isFinalized = $estudio->estado === 'finalizado';
                    $disabled = $isFinalized ? 'disabled' : '';
                    
                    // Obtener el valor actual del campo, manejando old() para valores de sesión anteriores
                    $valorHormonal = old('valor_hormonal', $estudio->valor_hormonal ?? '');
                @endphp
            
                <select class="form-control" name="valor_hormonal" id="valor_hormonal" {{ $disabled }}>
                    <option value="" disabled {{ empty($valorHormonal) ? 'selected' : '' }}>Selecciona un valor</option>
                    <option value="Trofico" {{ $valorHormonal === 'Trofico' ? 'selected' : '' }}>Trófico</option>
                    <option value="Atrofico" {{ $valorHormonal === 'Atrofico' ? 'selected' : '' }}>Atrófico</option>
                    <option value="Hipotrofico" {{ $valorHormonal === 'Hipotrofico' ? 'selected' : '' }}>Hipotrófico</option>
                    <option value="Trofismo disociado" {{ $valorHormonal === 'Trofismo disociado' ? 'selected' : '' }}>Trofismo Disociado o Irregular</option>
                </select>
                <p></p>
            </div>

            <div class="form-group">
                <label for="fecha_lectura">Fecha Lectura:</label>
                @php
                    // Determinar si el campo debe estar deshabilitado
                    $isFinalized = $estudio->estado === 'finalizado';
                    $disabled = $isFinalized ? 'disabled' : '';
            
                    // Obtener el valor actual del campo, manejando old() para valores de sesión anteriores
                    $fechaLectura = old('fecha_lectura', $estudio->fecha_lectura ?? '');
                @endphp
            
                <input type="date" class="form-control" id="fecha_lectura" name="fecha_lectura" 
                       value="{{ $fechaLectura ? \Carbon\Carbon::parse($fechaLectura)->format('Y-m-d') : '' }}" {{ $disabled }}>
                <p></p>
            </div>

            <div class="form-group">
                <label for="valor_hormonal_HC">Valor Hormonal HC:</label>
                @php
                    // Determinar si el campo debe estar deshabilitado
                    $isFinalized = $estudio->estado === 'finalizado';
                    $disabled = $isFinalized ? 'disabled' : '';
            
                    // Obtener el valor actual del campo, manejando old() para valores de sesión anteriores
                    $valorHormonalHC = old('valor_hormonal_HC', $estudio->valor_hormonal_HC ?? '');
                @endphp
            
                <select class="form-control" name="valor_hormonal_HC" id="valor_hormonal_HC" {{ $disabled }}>
                    <option value="" disabled {{ empty($valorHormonalHC) ? 'selected' : '' }}>Selecciona un valor</option>
                    <option value="SI" {{ $valorHormonalHC === 'SI' ? 'selected' : '' }}>Sí</option>
                    <option value="NO" {{ $valorHormonalHC === 'NO' ? 'selected' : '' }}>No</option>
                </select>
                <p></p>
            </div>

            <div class="form-group">
                <label for="cambios_reactivos">Cambios Reactivos:</label>
                @php
                    // Determinar si el campo debe estar deshabilitado
                    $isFinalized = $estudio->estado === 'finalizado';
                    $disabled = $isFinalized ? 'disabled' : '';
            
                    // Asegurarse de que $cambios_reactivos sea un array
                    $cambiosReactivos = is_array($estudio->cambios_reactivos) 
                                        ? $estudio->cambios_reactivos 
                                        : explode(',', $estudio->cambios_reactivos ?? '');
                @endphp
            
                <select class="select2" id="cambios_reactivos" name="cambios_reactivos[]" multiple="multiple" style="width: 100%;" {{ $disabled }}>
                    <option value="Asociados inflamacion_leve" {{ in_array('Asociados inflamacion_leve', $cambiosReactivos) ? 'selected' : '' }}>Asociados a inflamación leve</option>
                    <option value="Asociados inflamacion moderada" {{ in_array('Asociados inflamacion moderada', $cambiosReactivos) ? 'selected' : '' }}>Asociados a inflamación moderada</option>
                    <option value="Asociados inflamacion severa" {{ in_array('Asociados inflamacion severa', $cambiosReactivos) ? 'selected' : '' }}>Asociados a inflamación severa</option>
                    <option value="Trastornos madurativos" {{ in_array('Trastornos madurativos', $cambiosReactivos) ? 'selected' : '' }}>Trastornos madurativos</option>
                    <option value="Efecto radioterapia" {{ in_array('Efecto radioterapia', $cambiosReactivos) ? 'selected' : '' }}>Efecto de radioterapia</option>
                    <option value="DIU" {{ in_array('DIU', $cambiosReactivos) ? 'selected' : '' }}>DIU</option>
                    <option value="Terapias hormonales" {{ in_array('Terapias hormonales', $cambiosReactivos) ? 'selected' : '' }}>Terapias hormonales</option>
                    <option value="Otros" {{ in_array('Otros', $cambiosReactivos) ? 'selected' : '' }}>Otros</option>
                </select>
                <p></p>
            </div>

            <div class="form-group">
                <label for="cambios_asoc_celula_pavimentosa">Cambios Asociados a Célula Pavimentosa:</label>
                @php
                    // Determinar si el campo debe estar deshabilitado
                    $isFinalized = $estudio->estado === 'finalizado';
                    $disabled = $isFinalized ? 'disabled' : '';
            
                    // Asegurarse de que $cambios_asoc_celula_pavimentosa sea un array
                    $cambiosAsocCelulaPavimentosa = is_array($estudio->cambios_asoc_celula_pavimentosa) 
                                                    ? $estudio->cambios_asoc_celula_pavimentosa 
                                                    : explode(',', $estudio->cambios_asoc_celula_pavimentosa ?? '');
                @endphp
            
                <select class="select2" id="cambios_asoc_celula_pavimentosa" name="cambios_asoc_celula_pavimentosa[]" multiple="multiple" style="width: 100%;" {{ $disabled }}>
                    <option value="Escamas anucleadas" {{ in_array('Escamas anucleadas', $cambiosAsocCelulaPavimentosa) ? 'selected' : '' }}>Escamas anucleadas</option>
                    <option value="Paraqueratosis" {{ in_array('Paraqueratosis', $cambiosAsocCelulaPavimentosa) ? 'selected' : '' }}>Paraqueratosis</option>
                    <option value="Binucleacion" {{ in_array('Binucleacion', $cambiosAsocCelulaPavimentosa) ? 'selected' : '' }}>Binucleacion</option>
                    <option value="Megalocariosis" {{ in_array('Megalocariosis', $cambiosAsocCelulaPavimentosa) ? 'selected' : '' }}>Megalocariosis</option>
                    <option value="Hipercromasia" {{ in_array('Hipercromasia', $cambiosAsocCelulaPavimentosa) ? 'selected' : '' }}>Hipercromasia</option>
                    <option value="Coilocitos" {{ in_array('Coilocitos', $cambiosAsocCelulaPavimentosa) ? 'selected' : '' }}>Coilocitos</option>
                    <option value="Anisocariosis" {{ in_array('Anisocariosis', $cambiosAsocCelulaPavimentosa) ? 'selected' : '' }}>Anisocariosis</option>
                    <option value="Anfofilia" {{ in_array('Anfofilia', $cambiosAsocCelulaPavimentosa) ? 'selected' : '' }}>Anfofilia</option>
                    <option value="Aros perinucleares" {{ in_array('Aros perinucleares', $cambiosAsocCelulaPavimentosa) ? 'selected' : '' }}>Aros perinucleares</option>
                </select>
                <p></p>
            </div>

            <div class="form-group">
                <label for="cambios_celula_glandulares">Anomalías en Células Glandulares:</label>
                @php
                    // Determinar si el campo debe estar deshabilitado
                    $isFinalized = $estudio->estado === 'finalizado';
                    $disabled = $isFinalized ? 'disabled' : '';
            
                    // Obtener el valor actual del campo, manejando old() para valores de sesión anteriores
                    $cambiosCelulaGlandulares = old('cambios_celula_glandulares', $estudio->cambios_celula_glandulares ?? '');
                @endphp
            
                <input 
                    type="text" 
                    class="form-control" 
                    id="cambios_celula_glandulares" 
                    name="cambios_celula_glandulares" 
                    value="{{ $cambiosCelulaGlandulares }}" 
                    {{ $disabled }}
                >
            </div>

            <div class="form-group">
                <label for="celula_metaplastica">Célula Metaplástica:</label>
                @php
                    // Determinar si el campo debe estar deshabilitado
                    $isFinalized = $estudio->estado === 'finalizado';
                    $disabled = $isFinalized ? 'disabled' : '';
            
                    // Decodificar el valor de celula_metaplastica en un array
                    $celulaMetaplastica = is_array($estudio->celula_metaplastica) 
                        ? $estudio->celula_metaplastica 
                        : (empty($estudio->celula_metaplastica) ? [] : explode(',', $estudio->celula_metaplastica));
                @endphp
            
                <select class="select2" id="celula_metaplastica" name="celula_metaplastica[]" multiple="multiple" style="width: 100%;" {{ $disabled }}>
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
                @php
                    // Determinar si el campo debe estar deshabilitado
                    $isFinalized = $estudio->estado === 'finalizado';
                    $disabled = $isFinalized ? 'disabled' : '';
            
                    // Obtener el valor actual del campo, manejando old() para valores de sesión anteriores
                    $otrasNeoMalignas = old('otras_neo_malignas', $estudio->otras_neo_malignas ?? '');
                @endphp
            
                <input 
                    type="text" 
                    class="form-control" 
                    id="otras_neo_malignas" 
                    name="otras_neo_malignas" 
                    value="{{ $otrasNeoMalignas }}" 
                    {{ $disabled }}
                >
            </div>

            <div class="form-group">
                <label for="toma">Toma:</label>
                @php
                    // Determinar si el campo debe estar deshabilitado
                    $isFinalized = $estudio->estado === 'finalizado';
                    $disabled = $isFinalized ? 'disabled' : '';
            
                    // Decodificar el valor de toma en un array
                    $toma = is_array($estudio->toma) ? $estudio->toma : explode(',', $estudio->toma ?? '');
                @endphp
            
                <select class="select2" id="toma" name="toma[]" multiple="multiple" style="width: 100%;" {{ $disabled }}>
                    <option value="Exo" {{ in_array('Exo', $toma) ? 'selected' : '' }}>Exo</option>
                    <option value="Endo" {{ in_array('Endo', $toma) ? 'selected' : '' }}>Endo</option>
                    <option value="Cupula" {{ in_array('Cupula', $toma) ? 'selected' : '' }}>Cúpula</option>
                </select>
            </div>

            <div class="form-group">
                <label for="recomendaciones">Recomendaciones:</label>
                @php
                    // Determinar si el campo debe estar deshabilitado
                    $isFinalized = $estudio->estado === 'finalizado';
                    $disabled = $isFinalized ? 'disabled' : '';
            
                    // Decodificar el valor de recomendaciones en un array
                    $recomendaciones = is_array($estudio->recomendaciones) 
                        ? $estudio->recomendaciones 
                        : explode(',', $estudio->recomendaciones ?? '');
                @endphp
            
                <select class="select2" id="recomendaciones" name="recomendaciones[]" multiple="multiple" style="width: 100%;" {{ $disabled }}>
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
                @php
                    // Determinar si el campo debe estar deshabilitado
                    $isFinalized = $estudio->estado === 'finalizado';
                    $disabled = $isFinalized ? 'disabled' : '';
            
                    // Decodificar el valor de microorganismos en un array
                    $microorganismos = is_array($estudio->microorganismos) 
                        ? $estudio->microorganismos 
                        : explode(',', $estudio->microorganismos ?? '');
                @endphp
            
                <select class="select2" id="microorganismos" name="microorganismos[]" multiple="multiple" style="width: 100%;" {{ $disabled }}>
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
                @php
                    // Determinar si el campo debe estar deshabilitado
                    $isFinalized = $estudio->estado === 'finalizado';
                    $disabled = $isFinalized ? 'disabled' : '';
            
                    // Decodificar el valor de resultado en un array si aún no se ha hecho en el controlador
                    $resultado = is_array($estudio->resultado) 
                        ? $estudio->resultado 
                        : explode(',', $estudio->resultado ?? '');
                @endphp
            
                <select class="select2" id="resultado" name="resultado[]" multiple="multiple" style="width: 100%;" {{ $disabled }}>
                    <option value="Insactifactorio" {{ in_array('Insactifactorio', $resultado) ? 'selected' : '' }}>Insactifactorio</option>
                    <option value="NEGATIVO" {{ in_array('NEGATIVO', $resultado) ? 'selected' : '' }}>NEGATIVO</option>
                    <option value="Anormalidad celulas epiteliales" {{ in_array('Anormalidad celulas epiteliales', $resultado) ? 'selected' : '' }}>Anormalidad de células epiteliales</option>
                    <option value="NEGATIVO LESION INTRAEPITELIAL" {{ in_array('NEGATIVO LESION INTRAEPITELIAL', $resultado) ? 'selected' : '' }}>NEGATIVO PARA LESIÓN INTRAEPITELIAL O MALIGNIDAD</option>
                    <option value="ASC-US" {{ in_array('ASC-US', $resultado) ? 'selected' : '' }}>ASC-US</option>
                    <option value="ASC-H" {{ in_array('ASC-H', $resultado) ? 'selected' : '' }}>ASC-H</option>
                    <option value="L-SIL" {{ in_array('L-SIL', $resultado) ? 'selected' : '' }}>LSIL</option>
                    <option value="HSIL" {{ in_array('HSIL', $resultado) ? 'selected' : '' }}>HSIL</option>
                    <option value="Celulas glandulares atípicas" {{ in_array('Celulas glandulares atípicas', $resultado) ? 'selected' : '' }}>Celulas glandulares atípicas</option>
                </select>
            </div>
            
        
    
            @php
                // Determinar si el estudio está finalizado
                $isFinalized = $estudio->estado === 'finalizado';
                $disabled = $isFinalized ? 'disabled' : '';
            @endphp

            <button type="submit" class="btn btn-primary" {{ $disabled }}>Actualizar</button>
            <button type="button" id="finalizarEstudio" class="btn btn-success" {{ $disabled }}>Finalizar Estudio</button>
            <a href="{{ route('estudios.index') }}" class="btn btn-secondary">Cancelar</a>
            <p></p>
        </form>
        

    </div>
    <p></p>
    
    <script>

        document.addEventListener('DOMContentLoaded', function() {
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
            });

            // Manejar clic en el botón "Finalizar"
            document.getElementById('finalizarEstudio').addEventListener('click', function(event) {
                event.preventDefault(); // Prevenir el comportamiento por defecto del botón

                // Cambiar la acción del formulario a la ruta de finalización
                document.getElementById('estudioForm').action = '{{ route('estudios.finally', $estudio->nro_servicio) }}';

                // Enviar el formulario
                document.getElementById('estudioForm').submit();
            });
        });
        

    </script>

</x-app-layout>