<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TurnoNocheController extends Controller
{
    //

    public function GuardarTurnoNoche(Request $request){
       $tabla_turnos = new \App\TurnoNoche;

       $post = $request->json()->all();

       
    }
}
