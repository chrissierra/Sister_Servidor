<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Ejemplo1 extends Controller
{

	public function test(){
		 $hola= \App\clientes_rrhh::find(2);

               var_dump($hola);


	}
    //
public function test1(Request $request){

	var_dump($request->post());
}

}
