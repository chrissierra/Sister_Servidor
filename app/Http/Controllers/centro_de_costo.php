<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class centro_de_costo extends Controller
{
    //
        public function ingresar_centro_de_costo(Request $request){
    	$post = $request->json()->all();
    	$centro_de_costo = new \App\centro_de_costo;
    	
    	for ($i=0; $i < count($post); $i++) { 
    		if( strlen($post[$i]['value']) < 1) abort(403, 'Unauthorized action.');
    	}

    	$centro_de_costo->nombre= $post[0]['value'];
    	$centro_de_costo->nombre_empresa = $post[1]['value'];
    	$centro_de_costo->save();
    	echo json_encode(array("estatus"=>'ok'));
    	//$planilla = new \App\mandantes;


    }

    public function actualizar_centro_de_costo(Request $request){
    	$post = $request->json()->all();
    	$centro_de_costo = \App\centro_de_costo::where('id', $post[1]['value']);   	
		$centro_de_costo->update(['nombre' => $post[0]['value']]);
    

    }


    public function get_centro_de_costo(Request $request){
    	$post = $request->json()->all();
    	$centro_de_costo = \App\centro_de_costo::where('nombre_empresa', $post['nombre_empresa'])->get();
    	return response()->json($centro_de_costo);
    }

    public function delete_centro_de_costo(Request $request){
    	$post = $request->json()->all();
    	$centro_de_costo = \App\centro_de_costo::where('id', $post[0]['value'])
    	 						->where('nombre_empresa', $post[1]['value'])->delete();
    }
}
