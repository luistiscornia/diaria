<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class PlanillaHorarioCab extends Authenticatable
{
	protected $guarded = [];
	public $timestamps = false;
	protected $table = 'planilla_horarios_cab';
	protected $primaryKey = 'id_planilla_horarios_cab';
	protected $fillable = [
			'id_planilla_horarios_cab', 'mes_correspondiente', 'ano_correspondiente',  'id_puesto_migratorio'
	];  

}
