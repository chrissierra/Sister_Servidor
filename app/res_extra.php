<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class res_extra extends Model
{
    //
    protected $table = 'res_extra';
    protected $guarded = [];
    /*
    empresa_id
	sucursal_id
	trabajador_id
	supervisor_id
	*/
        public function empresa()
    {
        return $this->hasOne('App\clientes_rrhh', 'id');
    }

        public function sucursal()
    {
        return $this->hasOne('App\sucursales', 'id');
    }

        public function trabajador()
    {
        return $this->hasOne('App\ingreso_empleados', 'id');
    }

        public function supervisor()
    {
        return $this->hasOne('App\ingreso_empleados', 'id');
    }            

}
