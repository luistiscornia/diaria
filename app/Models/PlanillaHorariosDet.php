<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanillaHorariosDet extends Model
{
    protected $table = 'planilla_horarios_det';

    protected $fillable = [
        // Agrega aqu¨ª los campos que puedan ser llenados masivamente
    ];

    public $timestamps = false;

    // Define la relaci¨®n con el modelo Historial
    public function historiales()
    {
        return $this->hasMany(Historial::class, 'id_planilla', 'id_planilla_horarios_det');
    }
}


