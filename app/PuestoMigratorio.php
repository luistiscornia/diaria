<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class PuestoMigratorio extends Authenticatable
{
    protected $guarded = [];
	public $timestamps = false;
	protected $table = 'puestos_migratorios';
	protected $primaryKey = 'id';
	protected $fillable = [
		'id', 'nombre', 'puestos_vinculados', 'fecha'
	];
	public function scopePuestosMigratorios($query, $type)
    {
        return $query->select('puestos_vinculados')
        ->where('id',$type);
    }

}
