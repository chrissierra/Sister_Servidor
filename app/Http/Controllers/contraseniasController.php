<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class contraseniasController extends Controller
{
    //
    public function ingresarClaves(Request $request){
    	 $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]

    	 echo json_encode($post);
    	 //$contrasenias = new \App\contraseñas;

    	 //$contraseñas->

    }
}
