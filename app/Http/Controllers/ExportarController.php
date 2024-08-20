<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\Exportar;
use App\Models\Estudio;
use App\Models\Personal;
use App\Models\Paciente;
use App\Models\User; // Importar el modelo Exportar
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; // Añadir esta línea
use App\Mail\EstudioMail; // Importar la clase de correo

class ExportarController extends Controller
{
    public function exportarDatos($nro_servicio)
    {
        // Crear una instancia del modelo Exportar
        $exportar = new Exportar();
        
        // Obtener los datos del estudio utilizando el método de la instancia
        $estudio = $exportar->getEstudio($nro_servicio);

        // Obtener los nombres de los usuarios creador del estudio
        $createdPapName = User::find($estudio->createdPap)->name ?? 'Desconocido';
        $createdDetalleName = User::find($estudio->createdDetalle)->name ?? 'Desconocido';

        $matriculaPap = User::find($estudio->createdPap)->matricula ?? 'Desconocido';
        $matriculaDetalle = User::find($estudio->createdDetalle)->matricula ?? 'Desconocido';

        // Dividir segun el tipo de estudio
        if ($estudio->tipo_estudio_id === 3) {
            
            $data = [
                'tipo_estudio' => $estudio->tipo_estudio_id,
                'nombre' => $estudio->paciente,
                'dni' => $estudio->documento_paciente,
                'medico' => $estudio->medico_solicitante,
                'informe_numero' => $nro_servicio,
                'fecha_entrada' => $estudio->fecha_carga,
                'material_remitido' => $estudio->materiales,
                'estado_especimen' => json_decode($estudio->estado_especimen, true),
                'celulas_pavimentosas' => json_decode($estudio->celulas_pavimentosas, true),
                'celulas_cilindricas' => json_decode($estudio->celulas_cilindricas, true),
                'valor_hormonal' => $estudio->valor_hormonal,
                'fecha_lectura' => $estudio->fecha_lectura,
                'valor_hormonal_HC' => $estudio->valor_hormonal_HC,
                'cambios_reactivos' => json_decode($estudio->cambios_reactivos, true),
                'cambios_asoc_celula_pavimentosa' => json_decode($estudio->cambios_asoc_celula_pavimentosa, true),
                'cambios_celula_glandulares' => $estudio->cambios_celula_glandulares,
                'celula_metaplastica' => json_decode($estudio->celula_metaplastica, true),
                'otras_neo_malignas' => $estudio->otras_neo_malignas,
                'toma' => json_decode($estudio->toma, true),
                'recomendaciones' => json_decode($estudio->recomendaciones, true),
                'microorganismos' => json_decode($estudio->microorganismos, true),
                'resultado' => json_decode($estudio->resultado, true),
                'fecha_pap_finalizado' => $estudio->fecha_pap_finalizado,
                'createdPap' => $createdPapName,
                'matriculaPap' => $matriculaPap,
            ];
        } else {
            $data = [
                'tipo_estudio' => $estudio->tipo_estudio_id,
                'nombre' => $estudio->paciente,
                'dni' => $estudio->documento_paciente,
                'hc' => '22856',
                'medico' => $estudio->medico_solicitante,
                'informe_numero' => $nro_servicio,
                'fecha_entrada' => $estudio->fecha_carga,
                'fecha_estudio_finalizado' => $estudio->fecha_estudio_finalizado,
                'material_remitido' => $estudio->materiales,
                'tecnica' => json_decode($estudio->tecnicas, true),
                'macroscopia' => $estudio->macro, 
                'microscopia' => $estudio->micro,
                'diagnostico' => $estudio->diagnostico,
                'ampliar_informe' => $estudio->ampliar_informe,
                'createdDetalle' => $createdDetalleName,
                'matriculaDetalle' => $matriculaDetalle,
            ];
        }

        // Generar el PDF utilizando la vista y los datos
        $pdf = PDF::loadView('estudios.export_estudio', $data);

        // Descargar el PDF
        return $pdf->download('datos.pdf');
    }


    // Método para enviar el PDF por correo
    public function enviarDatosPorCorreo(Request $request, $nro_servicio)
    {
        $exportar = new Exportar();
        $estudio = $exportar->getEstudio($nro_servicio);

        $createdPapName = User::find($estudio->createdPap)->name ?? 'Desconocido';
        $createdDetalleName = User::find($estudio->createdDetalle)->name ?? 'Desconocido';
        $matriculaPap = User::find($estudio->createdPap)->matricula ?? 'Desconocido';
        $matriculaDetalle = User::find($estudio->createdDetalle)->matricula ?? 'Desconocido';

        if ($estudio->tipo_estudio_id === 3) {
            $data = [
                'tipo_estudio' => $estudio->tipo_estudio_id,
                'nombre' => $estudio->paciente,
                'dni' => $estudio->documento_paciente,
                'medico' => $estudio->medico_solicitante,
                'informe_numero' => $nro_servicio,
                'fecha_entrada' => $estudio->fecha_carga,
                'material_remitido' => $estudio->materiales,
                'estado_especimen' => json_decode($estudio->estado_especimen, true),
                'celulas_pavimentosas' => json_decode($estudio->celulas_pavimentosas, true),
                'celulas_cilindricas' => json_decode($estudio->celulas_cilindricas, true),
                'valor_hormonal' => $estudio->valor_hormonal,
                'fecha_lectura' => $estudio->fecha_lectura,
                'valor_hormonal_HC' => $estudio->valor_hormonal_HC,
                'cambios_reactivos' => json_decode($estudio->cambios_reactivos, true),
                'cambios_asoc_celula_pavimentosa' => json_decode($estudio->cambios_asoc_celula_pavimentosa, true),
                'cambios_celula_glandulares' => $estudio->cambios_celula_glandulares,
                'celula_metaplastica' => json_decode($estudio->celula_metaplastica, true),
                'otras_neo_malignas' => $estudio->otras_neo_malignas,
                'toma' => json_decode($estudio->toma, true),
                'recomendaciones' => json_decode($estudio->recomendaciones, true),
                'microorganismos' => json_decode($estudio->microorganismos, true),
                'resultado' => json_decode($estudio->resultado, true),
                'fecha_pap_finalizado' => $estudio->fecha_pap_finalizado,
                'createdPap' => $createdPapName,
                'matriculaPap' => $matriculaPap,
            ];
        } else {
            $data = [
                'tipo_estudio' => $estudio->tipo_estudio_id,
                'nombre' => $estudio->paciente,
                'dni' => $estudio->documento_paciente,
                'hc' => '22856',
                'medico' => $estudio->medico_solicitante,
                'informe_numero' => $nro_servicio,
                'fecha_entrada' => $estudio->fecha_carga,
                'fecha_estudio_finalizado' => $estudio->fecha_estudio_finalizado,
                'material_remitido' => $estudio->materiales,
                'tecnica' => json_decode($estudio->tecnicas, true),
                'macroscopia' => $estudio->macro, 
                'microscopia' => $estudio->micro,
                'diagnostico' => $estudio->diagnostico,
                'ampliar_informe' => $estudio->ampliar_informe,
                'createdDetalle' => $createdDetalleName,
                'matriculaDetalle' => $matriculaDetalle,
            ];
        }

        $datos = Personal::getEstudioPaciente($nro_servicio);
        

        // Extraer el persona_salutte_id
        $persona_id = $datos->persona_salutte_id;

        $contacto = Paciente::findEmail($persona_id);

        // Verificar si el contacto es nulo o si el email es nulo
        if ($contacto && $contacto->email) {
            // Generar el PDF
            $pdf = Pdf::loadView('estudios.export_estudio', $data);

            // Definir el nombre del archivo
            $pdfFilename = 'Informe Anatomía Patológica HU.' . $nro_servicio . '.pdf';
            $pdfPath = storage_path('app/public/' . $pdfFilename);
            $pdf->save($pdfPath);

            // Enviar el PDF por correo
            Mail::to($contacto->email)->send(new EstudioMail($data, $pdfPath));

            // Actualizar el estado del estudio solo si el email es válido y el envío fue exitoso
            Estudio::where('nro_servicio', $nro_servicio)->update(['enviado' => 1]);

            // Eliminar el archivo temporal después de enviar
            unlink($pdfPath);

            //Obtener la posicion mediante una consulta para redireccionar
            $posicion = Estudio::getPosition($nro_servicio);
            $estudios_por_pagina = 20;
            $pagina = ceil($posicion / $estudios_por_pagina);

            
            return redirect()->route('estudios.index', [
                'page' => $pagina,
                'finalizado' => $nro_servicio
            ])->with('success', 'Estudio enviado por correo con éxito');
        } else {
            // Redirigir con un mensaje de error si no hay email
        
            $posicion = Estudio::getPosition($nro_servicio);
            $estudios_por_pagina = 20;
            $pagina = ceil($posicion / $estudios_por_pagina);

            
            return redirect()->route('estudios.index', [
                'page' => $pagina,
                'finalizado' => $nro_servicio
            ])->with('error', 'El paciente no cuenta con un email registrado');
        }
    }
}
