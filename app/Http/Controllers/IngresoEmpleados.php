<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IngresoEmpleados extends Controller
{
    //
    public function Enrolamiento(Request $request){

    	$post = $request->json()->all();

        $jefatura = \App\jefaturas::where('id', $post['jefatura_id'])->get();
        $cargos = \App\cargos::where('id', $post['cargo_id'])->get();
        $sucursal_nombre = \App\sucursales::where('id', $post['sucursal_id'])->get();
    	$planilla = new \App\ingreso_empleados;

    	foreach ($post as $key => $value) {
    		$planilla->$key = $value;
    	}

        $planilla->jefatura = $jefatura[0]['nombre'];
        $planilla->cargo_nombre = $cargos[0]['cargo'];
        $planilla->sucursal_nombre = $sucursal_nombre[0]['nombre'];




    	$planilla->save();
    	echo json_encode($post);

    }



        public function Actualizacion_registro_Trabajadores(Request $request){

        $post = $request->json()->all();

        $planilla =  \App\ingreso_empleados::where('id', $post['id']);
        
        $sucursal_nombre = \App\sucursales::where('id', $post['sucursal_id'])->get();

        echo "$post['sucursal_id']" . $post['sucursal_id'];

        echo $sucursal_nombre[0]['nombre'];
        
        foreach ($post as $key => $value) {
            $planilla->update([$key => $value]);
        }

        $planilla->update(['sucursal_nombre' => $sucursal_nombre[0]['nombre']]);
        echo json_encode($post);

    }


}
