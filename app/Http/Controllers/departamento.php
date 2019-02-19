<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class departamento extends Controller
{
    //

    public function ingresardepartamento(Request $request){
    	$post = $request->json()->all();
    	$departamento = new \App\departamento;
    	$centro_costo = \App\centro_de_costo::where('id', $post[1]['value'])->get();
    	for ($i=0; $i < count($post); $i++) { 
    		if( strlen($post[$i]['value']) < 1) abort(403, 'Unauthorized action.');
    	}

    	$departamento->nombre= $post[0]['value'];
    	$departamento->centrocosto_id = $post[1]['value'];
    	$departamento->centro_costo_nombre = $centro_costo[0]['nombre'];
    	$departamento->jefatura_id = $post[2]['value'];
    	$departamento->nombre_empresa = $post[3]['value'];
    	$departamento->trabajador_encargado_id = $post[4]['value'];
	    $departamento->save();
    	echo json_encode(array("estatus"=>'ok'));
    	//$planilla = new \App\mandantes;


    }

    public function actualizardepartamento(Request $request){
    	$post = $request->json()->all();
    	$centro_costo = \App\centro_de_costo::where('id', $post[1]['value'])->get();
    	$departamento = \App\departamento::where('id', $post[0]['id_valor']);  	
		$departamento->update(['nombre' => $post[0]['value']]);
    	$departamento->update(['centrocosto_id' => $post[1]['value']]);
    	$departamento->update(['centro_costo_nombre' => $centro_costo[0]['nombre'];
    	$departamento->update(['jefatura_id' => $post[2]['value']]);
    	$departamento->update(['nombre_empresa' => $post[3]['value']]);
    	$departamento->update(['trabajador_encargado_id' => $post[4]['value']]);
    

    }


    public function getdepartamento(Request $request){
    	$post = $request->json()->all();
    	$departamento = \App\departamento::where('nombre_empresa', $post['nombre_empresa'])->get();
    	return response()->json($departamento);
    }

    public function deletedepartamento(Request $request){
    	$post = $request->json()->all();
    	$departamento = \App\departamento::where('id', $post[0]['id_valor'])->delete();
    }
}
