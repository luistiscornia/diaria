<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class PlanillaHorarioDet extends Authenticatable
{
	protected $guarded = [];
	public $timestamps = false;
	protected $table = 'planilla_horarios_det';
	protected $primaryKey = 'id_planilla_horarios_det';
	protected $fillable = [
			'id_planilla_horarios_det', 'nota', 'id_franja_horaria', 'id_planilla_horarios_cab', 'presente', 'sector', 'puesto', 'rel_temp'
	];

}
