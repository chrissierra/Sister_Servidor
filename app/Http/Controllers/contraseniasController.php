<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class contraseniasController extends Controller
{
    //
    public function ingresarClaves(Request $request){
    	
    	 $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]

    	$peo="";

    	 for ($i=0; $i < count($post); $i++) { 
    	 	# code...
    	 	$peo = $post[$i]['label'];
    	 }
    	 echo json_encode($peo);
    	 //$contrasenias = new \App\contraseñas;

    	 //$contraseñas->

    }
}
