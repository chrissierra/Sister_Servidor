<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class hitosController extends Controller
{
    //

    public function ingresarHitos(Request $request){
    	$post = $request->json()->all();
    	echo json_encode($post);
    }


    public function VisualizarHitos(Request $request){
    	
    }
}
