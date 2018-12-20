<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class mandanteController extends Controller
{
    //
    public function ingresarMandante(Request $request){
    	$post = $request->json()->all();

    	$planilla = new \App\mandantes;
    }

    public function actualizarMandante(Request $request){
    	
    }


    public function getMandante(Request $request){
    	
    }

    public function deleteMandante(Request $request){
    	
    }

}
