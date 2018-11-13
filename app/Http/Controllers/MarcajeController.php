<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarcajeController extends Controller
{
    //
    public $dia_e, $dia_s, $anio, $mes, $cuantia_esperada, $cuantia_diferencia_real_esperada;

    function __construct(){

        date_default_timezone_set('America/Santiago');
    		$this->mes = date('m')*1;
    		$this->anio = date('Y');
        $this->dia_e = (date('d') *1) . 'e';
        $this->dia_s = (date('d') *1) . 's';
        $this->fecha = date('d/m/Y');
    } // Fin función __construct


    public function SituacionMarcajeActual(Request $request){

	    $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
		  $planilla = \App\ingreso_empleados::where('id', $post['id'])->get();
      $trabajaDiaEnCurso = ( strcmp($planilla[0]['horario_con_o_sin_turnos'], 'Turnos') == 0 ) ? $this->TrabajaDiaEnCursoTurnos($post['id']) : $this->BooleanTieneTurno($post['id']); // Si 0, no tiene turno 
        $Entrada = ( $trabajaDiaEnCurso == 1 ) ? $this->VerificaMovimiento($post['id'], 'entrada') : 'Libre';
        $Salida = ( $trabajaDiaEnCurso == 1 ) ? $this->VerificaMovimiento($post['id'], 'salida') : 'Libre';
        $respuesta = new \stdClass();
        $respuesta->trabajaDiaEnCurso = $trabajaDiaEnCurso;
        $respuesta->Entrada = $Entrada;
        $respuesta->Salida = $Salida;
        $respuesta->TipoTurno = $planilla[0]['horario_con_o_sin_turnos'];
        $respuesta->EstatusEntrada = ( $Entrada == 0  ) ? '' : $this->VerificaPorParametroMovimiento($post['id'], 'entrada', 'status_entrada');
        $respuesta->EstatusSalida = ( $Salida == 0  ) ? '' : $this->VerificaPorParametroMovimiento($post['id'], 'salida', 'status_salida');
        echo json_encode($respuesta);
        //echo json_encode($this->fecha);

    } // Fin función SituacionMarcajeActual


    private function TrabajaDiaEnCursoTurnos($id){

		$planilla = \App\turnos::where('trabajador_id', $id)
		->where('mes', $this->mes)
		->where('anio', $this->anio);

       if($planilla->count()>0){
        $valor_a_retornar = ( strlen($planilla->get()[0][$this->dia_e]) == 0 ) ? 'Libre' : 1;
       	return $valor_a_retornar;
       }else{
       	return 'No tiene horario';
       }

    } // Fin función TrabajaDiaEnCursoTurnos


    private function TrabajaDiaEnCursoFijos($id){

      $planilla = \App\turnosfijos::where('trabajador_id', $id);
      $valor = (-1 + date('N') ) . 'e';
      echo json_encode($valor);
       if($planilla->count()>0){
        
        $valor_a_retornar = ( strlen($planilla->get()[0][$valor]) == 0 ) ? 'Libre' : 1;   
              
        return $valor_a_retornar;
     
       }else{
        return $valor_a_retornar ='No tiene horario';
      //  echo json_encode($valor_a_retornar);                   

       }
  
    } // Fin función TrabajaDiaEnCursoTurnos





    private function BooleanTieneTurno($id){

    $planilla = \App\turnosFijos::where('trabajador_id', $id);

       if($planilla->count() > 0){        
        return 1;
       }else{
        return 0;
       }

    } // Fin función BooleanTieneTurno


    private function VerificaMovimiento($id, $movimiento){

		$planilla = \App\asistencia::where('id_trabajador', $id)
		->where('fecha', $this->fecha)
		->where('tipo_movimiento', $movimiento);

       if($planilla->count()>0){
       	return $planilla->get()[0]['hora'];
       }else{
       	return 0;  // Si entrega 0 ; quiere decir que no hay movimiento, o entrada o salida.
       }

    } // Fin función VerificaMovimiento



    private function VerificaPorParametroMovimiento($id, $movimiento, $columna){

		$planilla = \App\asistencia::where('id_trabajador', $id)
		->where('fecha', $this->fecha)
		->where('tipo_movimiento', $movimiento);

       if($planilla->count()>0){
       	return $planilla->get()[0][$columna];
       }else{
       	return 0;  // Si entrega 0 ; quiere decir que no hay movimiento, o entrada o salida.
       }

    } // Fin función VerificaMovimiento


    public function MarcarMovimiento(Request $request){
        $tabla_asistencia = new \App\asistencia;
	      $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
      
        $planilla = \App\ingreso_empleados::where('id', $post['id'])->get();
      
  
        $turnos = \App\turnos::where([ ['trabajador_id', $post['id']], ['mes',  $this->mes], ['anio', $this->anio] ]);
          
        
        $trabajaDiaEnCurso = ( strcmp($planilla[0]['horario_con_o_sin_turnos'], 'Turnos') == 0 ) ? $this->TrabajaDiaEnCursoTurnos($post['id']) : $this->TrabajaDiaEnCursoFijos($post['id']) ;

       
        $Entrada = ( $trabajaDiaEnCurso == 1 ) ? $this->VerificaMovimiento($post['id'], 'entrada') : 'Libre';

        if($Entrada === 0){
         
            $tabla_asistencia->rut = $planilla[0]['rut'];
            $tabla_asistencia->id_trabajador  = $planilla[0]['id'];
            $tabla_asistencia->tipo_movimiento  = 'entrada';
            $tabla_asistencia->fecha = date('d/m/Y');
            $tabla_asistencia->hora = date('H:i:s');
            $tabla_asistencia->usuario_cliente = $planilla[0]['nombre_empresa_usuario_plataforma'];
            $tabla_asistencia->nombre = $planilla[0]['nombre'];
            $tabla_asistencia->apellido = $planilla[0]['apellido'];
            $tabla_asistencia->status_entrada = $this->GetHoraMovimiento($post['id'], 'entrada', (date('H') + ( date('i') /60 )) , $planilla[0]['horario_con_o_sin_turnos']) ;  // Atraso o no
            $tabla_asistencia->status_salida = '';  // Atraso o no
            $tabla_asistencia->cuantia_entrada = date('H')+ (date('i') /60 ) ;
            $tabla_asistencia->cuantia_salida = '';
            $tabla_asistencia->mes = date('m');
            $tabla_asistencia->anio = date('Y');
            $tabla_asistencia->dia = date('d');
            $tabla_asistencia->cuantia_esperada = $this->cuantia_esperada;
            $tabla_asistencia->cuantia_diferencia_real_esperada = $this->cuantia_diferencia_real_esperada;
            $tabla_asistencia->save();
        }
        
        $Salida = ( $trabajaDiaEnCurso == 1 ) ? $this->VerificaMovimiento($post['id'], 'salida') : 'Libre';
        if($Salida === 0 && $Entrada !== 0){
            $tabla_asistencia->rut = $planilla[0]['rut'];
            $tabla_asistencia->id_trabajador  = $planilla[0]['id'];
            $tabla_asistencia->tipo_movimiento  = 'salida';
            $tabla_asistencia->fecha = date('d/m/Y');
            $tabla_asistencia->hora = date('H:i:s');
            $tabla_asistencia->usuario_cliente = $planilla[0]['nombre_empresa_usuario_plataforma'];
            $tabla_asistencia->nombre = $planilla[0]['nombre'];
            $tabla_asistencia->apellido = $planilla[0]['apellido'];
            $tabla_asistencia->status_entrada = '';  // Atraso o no
            $tabla_asistencia->status_salida = $this->GetHoraMovimiento($post['id'], 'salida', (date('H') + ( date('i') /60 )) , $planilla[0]['horario_con_o_sin_turnos']) ;  // Atraso o no
            $tabla_asistencia->cuantia_entrada = '' ;
            $tabla_asistencia->cuantia_salida = date('H') + ( date('i') /60 );
            $tabla_asistencia->mes = date('m');
            $tabla_asistencia->anio = date('Y');
            $tabla_asistencia->dia = date('d');
            $tabla_asistencia->cuantia_esperada = $this->cuantia_esperada;
            $tabla_asistencia->cuantia_diferencia_real_esperada = $this->cuantia_diferencia_real_esperada;
            $tabla_asistencia->save();
        }
         // echo json_encode($Salida);
        //echo json_encode($this->fecha);

    } // Fin función SituacionMarcajeActual




    private function GetHoraMovimiento($id, $movimiento, $cuantia_actual, $Turnos_o_Fijos){

    if($Turnos_o_Fijos == 'Turnos'){
          $planilla = \App\turnos::where('trabajador_id', $id)
          ->where('mes', $this->mes)
          ->where('anio', $this->anio);

          $dia_e = $this->dia_e;
          $dia_s = $this->dia_s;
    }else{
         $planilla = \App\turnosfijos::where('trabajador_id', $id);
          $dia_e =   (-1 + date('N') ) . 'e';
          $dia_s =   (-1 + date('N') ) . 's';

    }

		

       if($movimiento == 'entrada'){
        $this->cuantia_esperada = (explode(':', $planilla->get()[0][$dia_e])[0] +  (explode(':', $planilla->get()[0][$dia_e])[1] / 60) );
        $this->cuantia_diferencia_real_esperada = $this->getDiferenciaCuantias($cuantia_actual, $this->cuantia_esperada, 'entrada');
        $estatus = (explode(':', $planilla->get()[0][$dia_e])[0] +  explode(':', $planilla->get()[0][$dia_e])[1] / 60 > $cuantia_actual ) ? 'ok' : 'Atraso';
       	return $estatus;
       }else{
        $this->cuantia_esperada = (explode(':', $planilla->get()[0][$dia_s])[0] +  (explode(':', $planilla->get()[0][$dia_s])[1] / 60)  );
        $estatus = (explode(':', $planilla->get()[0][$dia_s])[0] +  explode(':', $planilla->get()[0][$dia_s])[1] / 60 > $cuantia_actual ) ? 'MenosHoras' : 'HoraExtra';
        $this->cuantia_diferencia_real_esperada = $this->getDiferenciaCuantias($cuantia_actual, $this->cuantia_esperada, 'salida');
       	return $estatus;
       }

    } // Fin función GetHoraMovimiento


    private function getDiferenciaCuantias($cuantia_actual, $cuantiaEsperada, $movimiento){
      /*if($cuantia_actual < $cuantiaEsperada && $movimiento == 'entrada')
        return $cuantiaEsperada - $cuantia_actual;

      if($cuantia_actual > $cuantiaEsperada && $movimiento == 'entrada') // 831 - 830
        return $cuantia_actual - $cuantiaEsperada;

      if($cuantia_actual < $cuantiaEsperada && $movimiento == 'salida')  // Salir antes de la hora
        return $cuantiaEsperada - $cuantia_actual;

      if($cuantia_actual > $cuantiaEsperada && $movimiento == 'salida') // Salir despues de la hora
        return $cuantia_actual - $cuantiaEsperada;*/



      if($movimiento == 'entrada') // 831 - 830
        return $cuantiaEsperada - $cuantia_actual;

      if($movimiento == 'salida')  // Salir antes de la hora 1831-1830
        return $cuantia_actual - $cuantiaEsperada;

    }


} // Fin Clase MarcajeController
