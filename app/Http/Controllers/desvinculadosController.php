<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class desvinculadosController extends Controller
{
    //
    public function desvincular(Request $request){

		$post = $request->json()->all();
    	
    	$planilla =  \App\ingreso_empleados::where('id', $post['id']);

    	$tablaDesvinculacion = new \App\TrabajadoresDesvinculados; 

    	//var_dump($planilla->get()[0]->toArray());
    	foreach ($planilla->get()[0]->toArray() as $key => $value) {
    	  	$tablaDesvinculacion->$key = $value;
    	//	echo $key . ' | ' . $value;
    	}

    	$tablaDesvinculacion->save();

    	$planilla->delete();
    	
    	echo json_encode(array("estatus"=>'ok'));
    }
}
