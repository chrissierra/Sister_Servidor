<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class hitosController extends Controller
{
    //

    public function ingresarHitos(Request $request){

    	$post = $request->json()->all();
    	$hitos =  new \App\hitos;
    	echo json_encode($post['nombre_empresa']);
    /*	$hitos->tipo_hito = $post[0]['value'];
    	$hitos->nombre_empresa = $post[1]['value'];
    	$hitos->mandante = $post[2]['value'];
    	$hitos->url1 = $post[3]['value'];
    	$hitos->url2 = $post[3]['value'];
    	$hitos->url3 = $post[3]['value'];
    	$hitos->comentario =$post[4]['value'];
    	$hitos->nombre_trabajador =$post[4]['value'];
    	$hitos->trabajador_id =$post[4]['value'];

    	$hitos->save();
    	echo json_encode(array("estatus"=>'ok'));*/
    }


    public function VisualizarHitos(Request $request){
    	
    }
}
