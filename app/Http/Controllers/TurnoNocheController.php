<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TurnoNocheController extends Controller
{
    //

    public function GuardarTurnoNoche(Request $request){
       $tabla_turnos = new \App\TurnoNoche;

       $post = $request->json()->all();

         foreach ($post as $key => $value) {
	           # code...
	      		  $tabla_turnos->$key = $value;
	      }

        $tabla_turnos->save();

    } // Fin función GuardarTurnoNoche

        public function GetTurnoNoche(Request $request){
		    $post = $request->json()->all();
		      
		    

    	    $tabla_turnos =  \App\TurnoNoche::where('id_trabajador', $post['id'])
       	  					->where('mes', $post['mes'])
    	  					->where('anio', $post['anio']); 	    

			return json_encode($tabla_turnos->get());

    } // Fin función GuardarTurnoNoche

    public function UpdateTurnoNoche(Request $request){
    	  $post = $request->json()->all();
		      
    	  $tabla_turnos =  \App\TurnoNoche::where('id_trabajador', $post['id'])
    	  					->where('mes', $post['mes'])
    	  					->where('anio', $post['anio']);
         foreach ($post as $key => $value) {
	           # code...
	      		  $tabla_turnos->update([$key => $value]);
	      }
		
		return json_encode(array("estatus" => "ok"));

    }
}
