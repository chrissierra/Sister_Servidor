<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class cargosController extends Controller
{
    //
    //
    public function ingresarCargo(Request $request){
    	$post = $request->json()->all();
    	$cargos = new \App\cargos;
    	
    	for ($i=0; $i < count($post); $i++) { 
    		if( strlen($post[$i]['value']) < 1) abort(403, 'Unauthorized action.');
    	}

    	$cargos->cargo = $post[0]['value'];
    	$cargos->nombre_empresa = $post[1]['value'];
    	$cargos->rut_empresa = $post[2]['value'];
    	$cargos->empresa_id = $post[3]['value'];
    	$cargos->hito =$post[4]['value'];
    	$cargos->descripcion =$post[4]['value'];
    	$cargos->save();
    	echo json_encode(array("estatus"=>'ok'));
    	//$planilla = new \App\mandantes;


    }

    public function actualizarCargo(Request $request){
    	$post = $request->json()->all();
    	$cargos = \App\cargos::where('cargo', $post[0]['value'])
    	 						->where('nombre_empresa', $post[1]['value']);    	
		$cargos->update(['cargo' => $post[0]['value']]);
    	$cargos->update(['hito' => $post[4]['value']]);
    	$cargos->update(['descripcion' => $post[5]['value']]);
    

    }


    public function getCargos(Request $request){
    	$post = $request->json()->all();
    	$cargos = \App\cargos::where('nombre_empresa', $post['nombre_empresa'])->get();
    	return response()->json($cargos);
    }

    public function deleteCargos(Request $request){
    	
    }
}
