<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResExtraController extends Controller
{
    //
    public function ingresarResExtra(Request $request)
    {
        //
		$post = $request->json()->all(); 
		var_dump($post);
       	\App\res_extra::create($post);

    } 


    public function ActualizarResExtra(Request $request)
    {
        //
		$post = $request->json()->all(); 
       

    } 


    public function GetResExtra_unico(Request $request)
    {
        //
		$post = $request->json()->all(); 
        $response = \App\res_extra::where('id', $post['id']);         
        return json_encode(array('response'=>  $response->get(), 'ok' => true, 'empresa'=> $response->first()->empresa()->get()  ));

    } 

    public function GetResExtra_porSucursal(Request $request)
    {
        //
		$post = $request->json()->all(); 
        $response = \App\res_extra::with('sucursal')->with('trabajador')->with('supervisor')->where('sucursal_id', $post['id']);  

        return json_encode(array('response' =>  $response->paginate(5), 'ok' => true )); 


    } 

    public function GetResExtra_porTrabajador(Request $request)
    {
        //
        $post = $request->json()->all(); 
        $response = \App\res_extra::with('sucursal')->with('trabajador')->with('supervisor')->where('trabajador_id', $post['id']);  

        return json_encode(array('response' =>  $response->paginate(5), 'ok' => true  )); 
       

    } 

        public function GetResExtra_porSupervisor(Request $request)
    {
        //
        $post = $request->json()->all(); 
        $response = \App\res_extra::with('sucursal')->with('trabajador')->with('supervisor')->where('supervisor_id', $post['id']);  

        return json_encode(array('response' =>  $response->paginate(5), 'ok' => true  )); 
       

    }



    public function GetResExtra_porMes(Request $request)
    {
        //
        $post = $request->json()->all(); 
        $response = \App\res_extra::with('sucursal')->with('trabajador')->with('supervisor')->where('mes', $post['mes']);  

        return json_encode(array('response' =>  $response->paginate(5), 'ok' => true  ));  
       

    } 

        public function GetResExtra_porDia(Request $request)
    {
        //
        $post = $request->json()->all(); 
        $response = \App\res_extra::with('sucursal')->with('trabajador')->with('supervisor')
        ->where('anio', $post['anio'])  
        ->where('mes', $post['mes'])  
        ->where('dia', $post['dia']);  

        return json_encode(array('response' =>  $response->paginate(5), 'ok' => true  ));  
       

    } 
}
