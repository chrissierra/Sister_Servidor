<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class horario_por_sucursal extends Controller
{
    //

      public function ingresar_horario_por_sucursal(Request $request){
    	$post = $request->json()->all();
    	$horario_por_sucursal = new \App\horario_por_sucursal;
    	$cuantiaEsperada = explode( ':', $post['hora_esperada'])[0] + (explode(':', $post['hora_esperada'])[1] / 60 );

    	for ($i=0; $i < count($post); $i++) { 
    		if( strlen($post[$i]['value']) < 1) abort(403, 'Unauthorized action.');
    	}

    	$horario_por_sucursal->cuantia_inferior = implode() $post[0]['value'];
    	$horario_por_sucursal->cuantia_superior = $post[1]['value'];
        $horario_por_sucursal->cantidad_trabajadores = $post[2]['value'];
        $horario_por_sucursal->feriados = $post[3]['value'];
        $horario_por_sucursal->feriado_irrenunciable = $post4]['value'];
        $horario_por_sucursal->dia = $post[5]['value'];
        $horario_por_sucursal->caso_especial = $post[6]['value'];
        $horario_por_sucursal->fecha_caso_especial = $post[7]['value'];
    	$horario_por_sucursal->save();
    	echo json_encode(array("estatus"=>'ok'));
    	//$planilla = new \App\mandantes;


    }

    public function actualizar_horario_por_sucursal(Request $request){
    	$post = $request->json()->all();
    	$horario_por_sucursal = \App\horario_por_sucursal::where('id', $post[0]['id_valor']);   


		$horario_por_sucursal->update(['cuantia_inferior' => $post[0]['value']]);
    	$horario_por_sucursal->update(['cuantia_superior' => $post[1]['value']]);
    	$horario_por_sucursal->update(['cantidad_trabajadores' => $post[2]['value']]);
        $horario_por_sucursal->update(['feriados' => $post[3]['value']]);
        $horario_por_sucursal->update(['feriado_irrenunciable' => $post[4]['value']]);
		$horario_por_sucursal->update(['dia' => $post[5]['value']]);
    	$horario_por_sucursal->update(['caso_especial' => $post[6]['value']]);
    	$horario_por_sucursal->update(['fecha_caso_especial' => $post[7]['value']]);        

    }


    public function get_horario_por_sucursal(Request $request){
    	$post = $request->json()->all();
    	$horario_por_sucursal = \App\horario_por_sucursal::where('nombre_empresa', $post['nombre_empresa'])->get();
    	return response()->json($horario_por_sucursal);
    }

    public function delete_horario_por_sucursal(Request $request){
    	$post = $request->json()->all();
    	$horario_por_sucursal = \App\horario_por_sucursal::where('id', $post[0]['id_valor'])->delete();
    }
}
