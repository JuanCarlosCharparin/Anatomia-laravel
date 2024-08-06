<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\Exportar; // Importar el modelo Exportar

class ExportarController extends Controller
{
    public function exportarDatos($nro_servicio)
    {
        // Crear una instancia del modelo Exportar
        $exportar = new Exportar();
        
        // Obtener los datos del estudio utilizando el método de la instancia
        $estudio = $exportar->getEstudio($nro_servicio);

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
                'tecnica' => $estudio->tecnicas,
                'macroscopia' => $estudio->macro,
                'microscopia' => $estudio->micro,
                'diagnostico' => $estudio->diagnostico,
            ];
        }

        // Generar el PDF utilizando la vista y los datos
        $pdf = PDF::loadView('estudios.export_estudio', $data);

        // Descargar el PDF
        return $pdf->download('datos.pdf');
    }
}