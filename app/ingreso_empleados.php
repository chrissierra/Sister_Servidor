<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ingreso_empleados extends Model
{
    //
    protected $table = 'ingreso_empleados'; 
    public $timestamps = false;
    /**
* The attributes that aren't mass assignable.
*
* @var array
*/
protected $guarded = [];
}
