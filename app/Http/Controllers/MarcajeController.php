<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarcajeController extends Controller
{
    //
    public $dia_e, $dia_s, $anio, $mes;

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
        $trabajaDiaEnCurso = ( strcmp($planilla[0]['horario_con_o_sin_turnos'], 'Turnos') == 0 ) ? $this->TrabajaDiaEnCursoTurnos($post['id']) : 'Fijos';
        $Entrada = ( $trabajaDiaEnCurso == 1 ) ? $this->VerificaMovimiento($post['id'], 'entrada') : 'Libre';
        $Salida = ( $trabajaDiaEnCurso == 1 ) ? $this->VerificaMovimiento($post['id'], 'salida') : 'Libre';
        $respuesta = new \stdClass();
        $respuesta->trabajaDiaEnCurso = $trabajaDiaEnCurso;
        $respuesta->Entrada = $Entrada;
        $respuesta->Salida = $Salida;

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
        $trabajaDiaEnCurso = ( strcmp($planilla[0]['horario_con_o_sin_turnos'], 'Turnos') == 0 ) ? $this->TrabajaDiaEnCursoTurnos($post['id']) : 'Fijos';
        $Entrada = ( $trabajaDiaEnCurso == 1 ) ? $this->VerificaMovimiento($post['id'], 'entrada') : 'Libre';
        if($Entrada == 0){
            $tabla_asistencia->rut = $planilla[0]['rut'];
            $tabla_asistencia->id_trabajador  = $planilla[0]['id'];
            $tabla_asistencia->tipo_movimiento  = 'entrada';
            $tabla_asistencia->fecha = date('d/m/Y');
            $tabla_asistencia->hora = date('H:i:s');
            $tabla_asistencia->usuario_cliente = $planilla[0]['nombre_empresa_usuario_plataforma'];
            $tabla_asistencia->nombre = $planilla[0]['nombre'];
            $tabla_asistencia->apellido = $planilla[0]['apellido'];
            $tabla_asistencia->status_entrada = $this->GetHoraMovimiento($post['id'], 'entrada', (date('H') + ( date('i') /60 )) ) ;  // Atraso o no
            $tabla_asistencia->status_salida = '';  // Atraso o no
            $tabla_asistencia->cuantia_entrada = date('H')+ (date('i') /60 ) ;
            $tabla_asistencia->cuantia_salida = '';
            $tabla_asistencia->mes = date('m');
            $tabla_asistencia->anio = date('Y');
            $tabla_asistencia->save();
        }
        $Salida = ( $trabajaDiaEnCurso == 1 ) ? $this->VerificaMovimiento($post['id'], 'salida') : 'Libre';
        if($Salida == 0 && $Entrada !== 0){
            $tabla_asistencia->rut = $planilla[0]['rut'];
            $tabla_asistencia->id_trabajador  = $planilla[0]['id'];
            $tabla_asistencia->tipo_movimiento  = 'salida';
            $tabla_asistencia->fecha = date('d/m/Y');
            $tabla_asistencia->hora = date('H:i:s');
            $tabla_asistencia->usuario_cliente = $planilla[0]['nombre_empresa_usuario_plataforma'];
            $tabla_asistencia->nombre = $planilla[0]['nombre'];
            $tabla_asistencia->apellido = $planilla[0]['apellido'];
            $tabla_asistencia->status_entrada = '';  // Atraso o no
            $tabla_asistencia->status_salida = $this->GetHoraMovimiento($post['id'], 'salida', (date('H') + ( date('i') /60 )) ) ;  // Atraso o no
            $tabla_asistencia->cuantia_entrada = date('H') + ( date('i') /60 ) ;
            $tabla_asistencia->cuantia_salida = '';
            $tabla_asistencia->mes = date('m');
            $tabla_asistencia->anio = date('Y');
            $tabla_asistencia->save();
        }
         // echo json_encode($Salida);
        //echo json_encode($this->fecha);

    } // Fin función SituacionMarcajeActual




    private function GetHoraMovimiento($id, $movimiento, $cuantia_actual){

		$planilla = \App\turnos::where('trabajador_id', $id)
		->where('mes', $this->mes)
		->where('anio', $this->anio);

       if($movimiento == 'entrada'){
        $estatus = (explode(':', $planilla->get()[0][$this->dia_e])[0] +  explode(':', $planilla->get()[0][$this->dia_e])[1] / 60 > $cuantia_actual ) ? 'ok' : 'Atraso';
       	return $estatus;
       }else{
        $estatus = (explode(':', $planilla->get()[0][$this->dia_s])[0] +  explode(':', $planilla->get()[0][$this->dia_s])[1] / 60 > $cuantia_actual ) ? 'MenosHoras' : 'HoraExtra';
       	return $estatus;
       }

    } // Fin función GetHoraMovimiento


} // Fin Clase MarcajeController
