<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    protected $table = 'historial_2';

    protected $fillable = [
        'nombre_agente_solicitado',
        'nombre_agente_solicitante',
        'id_planilla'
    ];

    public $timestamps = false;

    // Define la relación con el modelo PlanillaHorariosDet
    public function planillaHorariosDet()
    {
        return $this->belongsTo(PlanillaHorariosDet::class, 'id_planilla', 'id_planilla_horarios_det');
    }
}

