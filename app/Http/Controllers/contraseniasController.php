<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class contraseniasController extends Controller
{
    //
    public function ingresarClaves(Request $request){
    	
    	 $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]

    	

    	 for ($i=0; $i < count($post); $i++) { 
    	 	# code...
    	     $contrasenias = new \App\contraseñas;
    	 	 $contrasenias->administrador_id = $post[$i]['administrador_id'];
     	 	 $contrasenias->clave = $post[$i]['clave'];
     	 	 $contrasenias->rol = $post[$i]['rol'];
     	 	 $contrasenias->sucursal = '';
     	 	 $contrasenias->cargo = '';
     	 	 $contrasenias->rut = $post[$i]['rut'];
     	 	 $contrasenias->nombre_empresa = $post[$i]['nombre_empresa'];
     	 	 $contrasenias->save();

    	 }
    	
    	 //$contrasenias = new \App\contraseñas;

    	 //$contraseñas->

    }
}
