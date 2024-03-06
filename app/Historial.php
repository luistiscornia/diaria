<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    // Nombre de la tabla en la base de datos
    protected $table = 'vw_historial_1';

    // Los campos que se pueden llenar masivamente
    protected $fillable = [
        'nombre_agente_solicitado',
        'nombre_agente_solicitante',
        'id_planilla',
        'fecha_cobertura',
        'tipo'
        
        // Agrega aquí los otros campos de tu tabla historial_1 si es necesario
    ];

    // Si no tienes timestamps (created_at, updated_at) en tu tabla, establece esto en false
    public $timestamps = false;
    
     // Definir la relación con PlanillaHorariosDet

    // Otras propiedades, métodos, relaciones, etc., según sea necesario
}
