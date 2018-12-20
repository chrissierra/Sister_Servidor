<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class mandanteController extends Controller
{
    //
    public function ingresarMandante(Request $request){
    	$post = $request->json()->all();
    	$mandantes = new \App\mandantes;
    	
    	for ($i=0; $i < count($post); $i++) { 
    		if( strlen($post[$i]['value']) < 1) abort(403, 'Unauthorized action.');
    	}

    	$mandantes->nombre_mandante = $post[0]['value'];
    	$mandantes->rut_mandante = $post[1]['value'];
    	$mandantes->clave = $post[2]['value'];
    	$mandantes->proveedor_servicios = $post[3]['value'];
    	$mandantes->hitos =$post[4]['value'];

    	echo json_encode(array("estatus"=>'ok'));
    	//$planilla = new \App\mandantes;


    }

    public function actualizarMandante(Request $request){
    	$post = $request->json()->all();
    	$mandantes = \App\mandantes::where('id', $post['id']);    	

    	$mandantes->update(['nombre_mandante' => $post[0]['value']]);
    	$mandantes->update(['rut_mandante' => $post[1]['value']]);
    	$mandantes->update(['clave' => $post[2]['value']]);
    	$mandantes->update(['proveedor_servicios' => $post[3]['value']]);
    	$mandantes->update(['hitos' =>$post[4]['value']]);

    }


    public function getMandante(Request $request){
    	$post = $request->json()->all();
    	$mandantes = \App\mandantes::where('id', $post['id'])->get();
    	return response()->json($mandantes);
    }

    public function deleteMandante(Request $request){
    	
    }

}
