<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class res_extra extends Model
{
    //
    protected $table = 'res_extra';
    /*
    empresa_id
	sucursal_id
	trabajador_id
	supervisor_id
	*/
        public function empresa()
    {
        return $this->hasOne('App\clientes_rrhh', 'empresa_id');
    }

        public function sucursal()
    {
        return $this->hasOne('App\sucursales', 'sucursal_id');
    }

        public function trabajador()
    {
        return $this->hasOne('App\ingreso_empleados', 'trabajador_id');
    }

        public function supervisor()
    {
        return $this->hasOne('App\ingreso_empleados', 'supervisor_id');
    }            

}
