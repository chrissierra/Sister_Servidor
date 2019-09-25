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
       

    } 

    public function GetResExtra_porSucursal(Request $request)
    {
        //
		$post = $request->json()->all(); 
       

    } 

    public function GetResExtra_porTrabajador(Request $request)
    {
        //
		$post = $request->json()->all(); 
       

    } 

        public function GetResExtra_porSupervisor(Request $request)
    {
        //
		$post = $request->json()->all(); 
       

    }



    public function GetResExtra_porMes(Request $request)
    {
        //
		$post = $request->json()->all(); 
       

    } 

        public function GetResExtra_porDia(Request $request)
    {
        //
		$post = $request->json()->all(); 
       

    } 
}
