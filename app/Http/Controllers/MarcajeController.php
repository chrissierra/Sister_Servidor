<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarcajeController extends Controller
{
    //

    public function SituacionMarcajeActual(Request $request){

	    $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
		$planilla = \App\ingreso_empleados::where('id', $post['id'])->get();
		$trabajaDiaEnCurso = ( strcmp($planilla[0]['horario_con_o_sin_turnos'], 'Turnos') == 0 ) ? $this->TrabajaDiaEnCursoTurnos($post['id']) : 'Fijos';
	    echo json_encode($trabajaDiaEnCurso);

    } // Fin función SituacionMarcajeActual

    private function TrabajaDiaEnCursoTurnos($id){
		date_default_timezone_set('America/Santiago');
		$mes = date('m')*1;
		$anio = date('Y'); 
		$dia = (date('d') *1) . 'e';  

		$planilla = \App\turnos::where('trabajador_id',  97)
		->where('mes', 8)
		->where('anio',2018);
		
       if($planilla->count()>0){
       	return strlen($planilla->get()[0][$dia]);
       }else{
       	return 'No tiene horario';
       }
    	
    	
    }	

} // Fin Clase MarcajeController
