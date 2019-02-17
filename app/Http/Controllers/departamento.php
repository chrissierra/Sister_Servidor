<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class departamento extends Controller
{
    //

    public function ingresardepartamento(Request $request){
    	$post = $request->json()->all();
    	$departamento = new \App\departamento;
    	
    	for ($i=0; $i < count($post); $i++) { 
    		if( strlen($post[$i]['value']) < 1) abort(403, 'Unauthorized action.');
    	}

    	$departamento->nombre= $post[0]['value'];
    	$departamento->centrocosto_id = $post[1]['value'];
    	$departamento->jefatura_id = $post[1]['value'];
    	$departamento->administrador_id = $post[1]['value'];
    	$departamento->trabajador_encargado_id = $post[1]['value'];
	    	$departamento->save();
    	echo json_encode(array("estatus"=>'ok'));
    	//$planilla = new \App\mandantes;


    }

    public function actualizardepartamento(Request $request){
    	$post = $request->json()->all();
    	$departamento = \App\departamento::where('departamento', $post[0]['value'])
    	 						->where('nombre_empresa', $post[1]['value']);    	
		$departamento->update(['nombre' => $post[0]['value']]);
    	$departamento->update(['centrocosto_id' => $post[4]['value']]);
    	$departamento->update(['jefatura_id' => $post[4]['value']]);
    	$departamento->update(['administrador_id' => $post[4]['value']]);
    	$departamento->update(['trabajador_encargado_id' => $post[4]['value']]);
    

    }


    public function getdepartamento(Request $request){
    	$post = $request->json()->all();
    	$departamento = \App\departamento::where('nombre_empresa', $post['nombre_empresa'])->get();
    	return response()->json($departamento);
    }

    public function deletedepartamento(Request $request){
    	$post = $request->json()->all();
    	$departamento = \App\departamento::where('departamento', $post[0]['value'])
    	 						->where('nombre_empresa', $post[1]['value'])->delete();
    }
}
