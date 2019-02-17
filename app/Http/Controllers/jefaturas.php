<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class jefaturas extends Controller
{
    //

     public function ingresarjefatura(Request $request){
    	$post = $request->json()->all();
    	$jefaturas = new \App\jefaturas;
    	
    	for ($i=0; $i < count($post); $i++) { 
    		if( strlen($post[$i]['value']) < 1) abort(403, 'Unauthorized action.');
    	}

    	$jefaturas->nombre= $post[0]['value'];
    	$jefaturas->centro_costo_id = $post[1]['value'];
    	$jefaturas->departamento_id = $post[2]['value'];
    	$jefaturas->save();
    	echo json_encode(array("estatus"=>'ok'));
    	//$planilla = new \App\mandantes;


    }

    public function actualizarjefatura(Request $request){
    	$post = $request->json()->all();
    	$jefaturas = \App\jefaturas::where('jefatura', $post[0]['value'])
    	 						->where('nombre_empresa', $post[1]['value']);    	
		$jefaturas->update(['nombre' => $post[0]['value']]);
    	$jefaturas->update(['centro_costo_id' => $post[4]['value']]);
    	$jefaturas->update(['departamento_id' => $post[5]['value']]);
    

    }


    public function getjefaturas(Request $request){
    	$post = $request->json()->all();
    	$jefaturas = \App\jefaturas::where('nombre_empresa', $post['nombre_empresa'])->get();
    	return response()->json($jefaturas);
    }

    public function deletejefaturas(Request $request){
    	$post = $request->json()->all();
    	$jefaturas = \App\jefaturas::where('jefatura', $post[0]['value'])
    	 						->where('nombre_empresa', $post[1]['value'])->delete();
    }
}
