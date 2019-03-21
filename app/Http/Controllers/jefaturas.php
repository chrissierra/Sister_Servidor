<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class jefaturas extends Controller
{
    //

     public function ingresarjefatura(Request $request){
    	$post = $request->json()->all();
    	$jefaturas = new \App\jefaturas;

        if(\App\centro_de_costo::where('id', $post[1]['value'])->count()>0){
          $centro_costo = \App\centro_de_costo::where('id', $post[1]['value'])->get()[0]['nombre'];  
      }else{
        $centro_costo = '';
      } 
    	if(\App\centro_de_costo::where('id', $post[2]['value'])->count()>0){
            $departamento = \App\departamento::where('id', $post[2]['value'])->get()[0]['nombre'];
        }else{
            $departamento = '';
        } 


        if( strlen($post[0]['value']) < 1) abort(404, 'Unauthorized action.' . $post[0]['value']);
        if( strlen($post[3]['value']) < 1) abort(404, 'Unauthorized action.' . $post[3]['value']);
    	/*for ($i=0; $i < count($post); $i++) { 

    		if( strlen($post[$i]['value']) < 1 && $i !== 2) abort(403, 'Unauthorized action.');
    	}*/

    	$jefaturas->nombre= $post[0]['value'];
    	$jefaturas->centro_costo_id = $post[1]['value'];
    	$jefaturas->departamento_id = $post[2]['value'];
        $jefaturas->nombre_empresa = $post[3]['value'];
        $jefaturas->centro_costo_nombre = $centro_costo;
        $jefaturas->departamento_nombre = $departamento;
    	$jefaturas->save();
    	echo json_encode(array("estatus"=>'ok'));
    	//$planilla = new \App\mandantes;


    }

    public function actualizarjefatura(Request $request){
    	$post = $request->json()->all();
    	$jefaturas = \App\jefaturas::where('id', $post[0]['id_valor']);   
        if(\App\centro_de_costo::where('id', $post[1]['value'])->count()>0){
          $centro_costo = \App\centro_de_costo::where('id', $post[1]['value'])->get()[0]['nombre'];  
      }else{
        $centro_costo = '';
      } 
        if(\App\centro_de_costo::where('id', $post[2]['value'])->count()>0){
            $departamento = \App\departamento::where('id', $post[2]['value'])->get()[0]['nombre'];
        }else{
            $departamento = '';
        } 


        if( strlen($post[0]['value']) < 1) abort(404, 'Unauthorized action.' . $post[0]['value']);
        if( strlen($post[3]['value']) < 1) abort(404, 'Unauthorized action.' . $post[3]['value']);

		$jefaturas->update(['nombre' => $post[0]['value']]);
    	$jefaturas->update(['centro_costo_id' => $post[1]['value']]);
    	$jefaturas->update(['departamento_id' => $post[2]['value']]);
        $jefaturas->update(['centro_costo_nombre' => $centro_costo[0]['nombre']]);
        $jefaturas->update(['departamento_nombre' => $departamento[0]['nombre']]);
    

    }


    public function getjefaturas(Request $request){
    	$post = $request->json()->all();
    	$jefaturas = \App\jefaturas::where('nombre_empresa', $post['nombre_empresa'])->get();
    	return response()->json($jefaturas);
    }

    public function deletejefaturas(Request $request){
    	$post = $request->json()->all();
    	$jefaturas = \App\jefaturas::where('id', $post[0]['id_valor'])->delete();
    }
}
