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
        'e.nro_servicio as nro_servicio',
        DB::raw("CONCAT(p.nombres, ' ', p.apellidos) as paciente"),
        'e.medico_solicitante as medico_solicitante',
        'p.documento as documento_paciente',
        'e.fecha_carga as fecha_carga',
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
        'def.diagnostico_presuntivo as diagnostico',
        'def.tecnicas',
        DB::raw("DATE(def.createdAt) as fecha_estudio_finalizado"),
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
        DB::raw("DATE(dpf.createdAt) as fecha_pap_finalizado")
    )
    ->leftJoin('personal as p', 'e.personal_id', '=', 'p.id')
    ->leftJoin('profesional as prof', 'e.profesional_id', '=', 'prof.id')
    ->leftJoin('detalle_estudio_finalizado as def', 'e.detalle_estudio_finalizado_id', '=', 'def.id')
    ->leftJoin('detalle_pap_finalizado as dpf', 'e.detalle_pap_finalizado_id', '=', 'dpf.id')
    ->where('e.nro_servicio', $nro_servicio)
    ->first();

        return $estudio;
    }
}