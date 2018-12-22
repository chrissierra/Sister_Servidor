<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class hitosController extends Controller
{
    //

    public function ingresarHitos(Request $request){

    	$post = $request->json()->all();
    	$hitos =  new \App\hitos;
    	//echo json_encode($post['url']['urlImagen']);
    	$hitos->tipo_hito = $post['tipo_hito'];
    	$hitos->nombre_empresa = $post['nombre_empresa'];
    	$hitos->mandante = $post['mandante'];
    	$hitos->url1 = $post['url']['urlImagen'];
    	$hitos->url2 =$post['url1']['urlImagen'];
    	$hitos->url3 =$post['url2']['urlImagen'];
    	$hitos->comentario =$post['comentario'];
    	$hitos->nombre_trabajador =$post['nombre_trabajador'];
    	$hitos->trabajador_id =$post['trabajador_id'];
    	$hitos->save();
    	echo json_encode(array("estatus"=>'ok'));
    }


    public function VisualizarHitos(Request $request){
    	$post = $request->json()->all();
    	$hitos = \App\hitos::where('proveedor_servicios', $post['proveedor_servicios'])->get();
    	return response()->json($hitos);    	
    }
}
