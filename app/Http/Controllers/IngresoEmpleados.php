<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IngresoEmpleados extends Controller
{
    //
    public function Enrolamiento(Request $request){

    	$post = $request->json()->all();

    	$planilla = new \App\ingreso_empleados;

    	foreach ($post as $key => $value) {
    		$planilla->$key = $value;
    	}


    	$planilla->save();
    	echo json_encode($post);

    }



        public function Actualizacion_registro_Trabajadores(Request $request){

        $post = $request->json()->all();

        $planilla =  \App\ingreso_empleados::where('id', $post['id']);

        foreach ($post as $key => $value) {
            $planilla->$key = $value;
        }


        $planilla->save();
        echo json_encode($post);

    }


}
