<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class libroremuneraciones extends Controller
{
    //
    public function diario(Request $request){

    	$post = $request->json()->all();

    	$tabla = \App\asistencia::where('usuario_cliente', $post['id'])->get();

    	return json_decode($tabla);

    }
}
