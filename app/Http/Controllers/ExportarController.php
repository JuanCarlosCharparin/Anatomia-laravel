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

        // Preparar los datos para la vista del PDF
        $data = [
            'nombre' => $estudio->paciente, // Nombre del paciente
            'dni' => $estudio->documento_paciente, // DNI del paciente
            'hc' => '22856', // Puedes modificar este campo según corresponda
            'medico' => $estudio->medico_solicitante, // Médico solicitante
            'informe_numero' => 'sdfgsdfg', // Puedes modificar este campo según corresponda
            'fecha_entrada' => $estudio->fecha_carga, // Fecha y hora de la carga
            'fecha_salida' => $estudio->fecha_estudio_finalizado,
            'fecha_salida' => $estudio->fecha_estudio_finalizado,
            'fecha_salida' => $estudio->fecha_estudio_finalizado,
            'material_remitido' => 'sdfgsdfgsd', // Puedes modificar este campo según corresponda
            'tecnica' => 'Inclusion en bloque de parafina-histoplast, Tincion con hematoxilina-eosina, Tincion 15 (BIOPUR), Tincion papanicolaou, GIEMSA, Acido peryodico de Schiff (PAS), Otros',
            'macroscopia' => $estudio->macro, // Macroscopia
            'microscopia' => $estudio->micro, // Microscopia
            'codigo' => 'A42-ACTINOMICOSIS', // Código del diagnóstico
            'diagnostico' => $estudio->diagnostico, // Diagnóstico
            'nota' => $estudio->observacion // Nota
        ];

        // Generar el PDF utilizando la vista y los datos
        $pdf = PDF::loadView('estudios.export_estudio', $data);

        // Descargar el PDF
        return $pdf->download('datos.pdf');
    }
}