<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class libroremuneraciones extends Controller
{
    //
    public function diario(Request $request){

    	$post = $request->json()->all();

    	$tabla = \App\asistencia::where('usuario_cliente', $post['id'])
    						->where('mes', explode('-', $post['dia'])[0]) // No debe decir $mes + 1 ...; solo $mes 
                            ->where('anio', explode('-', $post['dia'])[2])
                            ->where('dia', explode('-', $post['dia'])[1])
                            ->get();
    	return json_decode($tabla);

    	/*
    	   $tablaAsistencia = \App\asistencia::where('id_trabajador', $id)
                            ->where('mes', $mes  ) // No debe decir $mes + 1 ...; solo $mes 
                            ->where('anio', $anio)
                            ->where('dia', $dia)
                            ->where('tipo_movimiento', $mov);
                            */

    }
}
