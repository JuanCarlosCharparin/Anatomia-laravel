<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Exportar extends Model
{
    public function getEstudio($nro_servicio)
    {
        // Ejecutar la consulta
        $estudio = DB::connection('mysql')->table('estudio as e')
        ->select(
            'e.nro_servicio AS nro_servicio',
            'e.tipo_estudio_id',
            DB::raw("CONCAT(p.nombres, ' ', p.apellidos) AS paciente"),
            'e.medico_solicitante AS medico_solicitante',
            DB::raw("GROUP_CONCAT(m.material SEPARATOR ', ') AS materiales"), 
            'p.documento AS documento_paciente',
            'e.fecha_carga AS fecha_carga',
            'def.macro',
            'def.fecha_macro',
            'def.micro',
            'def.fecha_inclusion',
            'def.fecha_corte',
            'def.fecha_entrega',
            'def.observacion',
            'def.maligno',
            'def.observacion_interna',
            'def.recibe',
            'def.tacos',
            'def.diagnostico_presuntivo AS diagnostico',
            'def.tecnicas',
            DB::raw("DATE(def.createdAt) AS fecha_estudio_finalizado"),
            'dpf.estado_especimen',
            'dpf.celulas_pavimentosas',
            'dpf.celulas_cilindricas',
            'dpf.valor_hormonal',
            'dpf.fecha_lectura',
            'dpf.valor_hormonal_HC',
            'dpf.cambios_reactivos',
            'dpf.cambios_asoc_celula_pavimentosa',
            'dpf.cambios_celula_glandulares',
            'dpf.celula_metaplastica',
            'dpf.otras_neo_malignas',
            'dpf.toma',
            'dpf.recomendaciones',
            'dpf.microorganismos',
            'dpf.resultado',
            DB::raw("DATE(dpf.createdAt) AS fecha_pap_finalizado")
        )
        ->leftJoin('personal AS p', 'e.personal_id', '=', 'p.id')
        ->leftJoin('profesional AS prof', 'e.profesional_id', '=', 'prof.id')
        ->leftJoin('detalle_estudio_finalizado AS def', 'e.detalle_estudio_finalizado_id', '=', 'def.id')
        ->leftJoin('detalle_pap_finalizado AS dpf', 'e.detalle_pap_finalizado_id', '=', 'dpf.id')
        ->leftJoin('material AS m', 'e.nro_servicio', '=', 'm.nro_servicio')
        ->where('e.nro_servicio', $nro_servicio)
        ->groupBy(
            'e.nro_servicio',
            'e.tipo_estudio_id',
            DB::raw("CONCAT(p.nombres, ' ', p.apellidos)"),
            'e.medico_solicitante',
            'p.documento',
            'e.fecha_carga',
            'def.macro',
            'def.fecha_macro',
            'def.micro',
            'def.fecha_inclusion',
            'def.fecha_corte',
            'def.fecha_entrega',
            'def.observacion',
            'def.maligno',
            'def.observacion_interna',
            'def.recibe',
            'def.tacos',
            'def.diagnostico_presuntivo',
            'def.tecnicas',
            DB::raw("DATE(def.createdAt)"),
            'dpf.estado_especimen',
            'dpf.celulas_pavimentosas',
            'dpf.celulas_cilindricas',
            'dpf.valor_hormonal',
            'dpf.fecha_lectura',
            'dpf.valor_hormonal_HC',
            'dpf.cambios_reactivos',
            'dpf.cambios_asoc_celula_pavimentosa',
            'dpf.cambios_celula_glandulares',
            'dpf.celula_metaplastica',
            'dpf.otras_neo_malignas',
            'dpf.toma',
            'dpf.recomendaciones',
            'dpf.microorganismos',
            'dpf.resultado',
            DB::raw("DATE(dpf.createdAt)")
        )
        ->first();

        return $estudio;
    }
}