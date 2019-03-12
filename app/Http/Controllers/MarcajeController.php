<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarcajeController extends Controller
{
    //
    public $dia_e, $dia_s, $anio, $mes, $cuantia_esperada, $cuantia_diferencia_real_esperada, $horaEntrada, $horaSalida, $horarioNoche;

    function __construct(){

        date_default_timezone_set('America/Santiago');
          $this->tiempo = time();
    		$this->mes = date('m')*1;
    		$this->anio = date('Y');
        $this->dia_e = (date('d') *1) . 'e';
        $this->dia_s = (date('d') *1) . 's';
        $this->fecha = date('d/m/Y');
         $this->turnoExtraEnCurso = 0;
         $this->horarioNoche = 0;

    } // Fin función __construct

/*
AGregado lo de abajo  el 21 12 2018 por turnos extas */


      private function VerificarUltimoMovimientoTurnoExtraPorParametroInterna($id){
                
                

                $ultimoMovimiento = \App\asistencia::where('id_trabajador', $id)
                ->where('turnoExtra', 1)
               ->orderBy('id', 'desc')
               ->take(1);
                            //  echo json_encode($ultimoMovimiento->get()[0]);
                            //  echo json_encode("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<");

               //echo json_encode($ultimoMovimiento->get()[0]['tiempo']);
              if($ultimoMovimiento->count() === 0 ){
                # Entrada
              // echo  json_encode("aca 1");
               return $this->turnoExtraEnCurso =0;
              }elseif($ultimoMovimiento->count() > 0 && $ultimoMovimiento->get()[0]['tipo_movimiento']=== 'salida'){
               // echo json_encode("aca 2");
               return  $this->turnoExtraEnCurso =0;
              }elseif($ultimoMovimiento->count() > 0 && $ultimoMovimiento->get()[0]['tipo_movimiento'] === 'entrada'){
               // echo json_encode("aca 3");
                return  $this->analizarMovimientoTurnosExtras( $ultimoMovimiento->get()[0]['tiempo'] );
              }elseif($ultimoMovimiento->count() > 0 && $ultimoMovimiento->get()[0]['tipo_movimiento']=== 'entrada' && ($this->tiempo- $ultimoMovimiento->get()[0]['tiempo'])>43200){
              //echo json_encode("aca 4");
               return  $this->turnoExtraEnCurso =0; // Se acabó por tiempo el turno extra, por lógica puede marcar turno, no está en turno extra. 
               // Por error de no marcar salida de turno extra
              }
    }

    private function analizarMovimientoTurnosExtras($objetoUltimoMov){
        if($this->tiempo - $objetoUltimoMov > 46800){
         
            #mas de 13 horas del ultimo movimiento entrada. Entrega entrada nuevamente. 
            $this->turnoExtraEnCurso = 0;
        }elseif($this->tiempo-$objetoUltimoMov<46800){
          
            #Entrega a marcar la salida, han pasado menos de 13 horas
           $this->turnoExtraEnCurso = 1;

        }
    }



    private function analizarMovimiento($objetoUltimoMov){
        if($this->tiempo-$objetoUltimoMov['tiempo']>46800){
            #mas de 13 horas del ultimo movimiento entrada. Entrega entrada nuevamente. 
            echo json_encode('entrada');
        }elseif($this->tiempo-$objetoUltimoMov['tiempo']<46800){
            #Entrega a marcar la salida, han pasado menos de 13 horas
            echo json_encode('salida');

        }
    }




/* Agregado lo de arriba el 21 12 2018 por turnos extras */




    public function SituacionMarcajeActual(Request $request){

	    $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
		  $planilla = \App\ingreso_empleados::where('id', $post['id'])->get();
      $trabajaDiaEnCurso = ( strcmp($planilla[0]['horario_con_o_sin_turnos'], 'Turnos') == 0 ) ? $this->TrabajaDiaEnCursoTurnos($post['id']) : $this->TrabajaDiaEnCursoFijos($post['id']); // Si 0, no tiene turno 
/*
@ La entrada y salida, debe dar una hora. No libre. Trabaja dia en curso da cuenta de si está libre o no.
*/
        $this->VerificarUltimoMovimientoTurnoExtraPorParametroInterna($post['id']);

          $Entrada = $this->VerificaMovimiento($post['id'], 'entrada');
          $Salida =  $this->VerificaMovimiento($post['id'], 'salida');
          $EntradaTurnoExtra = $this->VerificaMovimientoTurnosExtras($post['id'], 'entrada');
          $SalidaTurnoExtra = $this->VerificaMovimientoTurnosExtras($post['id'], 'salida');

    //    $Entrada = ( $trabajaDiaEnCurso == 1 ) ? $this->VerificaMovimiento($post['id'], 'entrada') : 'Libre';
     //   $Salida = ( $trabajaDiaEnCurso == 1 ) ? $this->VerificaMovimiento($post['id'], 'salida') : 'Libre';
        $respuesta = new \stdClass();
        $respuesta->trabajaDiaEnCurso = $trabajaDiaEnCurso;        
        $respuesta->TurnoYaRealizado = ( strcmp($planilla[0]['horario_con_o_sin_turnos'], 'Turnos') == 0 ) ? $this->TrabajaDiaEnCursoTurnos($post['id'])  :  $this->TrabajaDiaEnCursoFijos($post['id']);
        $respuesta->Entrada = $Entrada;
        $respuesta->Salida = $Salida;
        $respuesta->TipoTurno = $planilla[0]['horario_con_o_sin_turnos'];
        $respuesta->EstatusEntrada = ( $Entrada == 0  ) ? '' : $this->VerificaPorParametroMovimiento($post['id'], 'entrada', 'status_entrada');
        $respuesta->EstatusSalida = ( $Salida == 0  ) ? '' : $this->VerificaPorParametroMovimiento($post['id'], 'salida', 'status_salida');
        $respuesta->horaEntrada = $this->horaEntrada;
        $respuesta->horaSalida = $this->horaSalida;
        $respuesta->EntradaTurnoExtra = $EntradaTurnoExtra;
        $respuesta->SalidaTurnoExtra = $SalidaTurnoExtra;
        $respuesta->TurnoExtraEnCurso =  $this->turnoExtraEnCurso;
        $respuesta->HorarioNoche = $this->horarioNoche;
        echo json_encode($respuesta);
        //echo json_encode($this->fecha);

    } // Fin función SituacionMarcajeActual


    private function TrabajaDiaEnCursoTurnos($id){

		$planilla = \App\Turnos::where('trabajador_id', $id)
		->where('mes', $this->mes)
		->where('anio', $this->anio);

       if($planilla->count()>0){
        $valor_a_retornar = ( strlen($planilla->get()[0][$this->dia_e]) == 0 ) ? 'Libre' : 1;
        $this->horaEntrada = $planilla->get()[0][$this->dia_e];
        $this->horaSalida = $planilla->get()[0][$this->dia_s];
       	return $valor_a_retornar;
       }else{

     
                    return 'No tiene horario';
               
       
       }

    } // Fin función TrabajaDiaEnCursoTurnos


    private function TrabajaDiaEnCursoFijos($id){

      $planilla = \App\turnosFijos::where('trabajador_id', $id);
      $valorE = (-1 + date('N') ) . 'e';
      $valorS = (-1 + date('N') ) . 's';
     // echo json_encode($valor);
       if($planilla->count()>0){
        
        $valor_a_retornar = ( strlen($planilla->get()[0][$valorE]) == 0 ) ? 'Libre' : 1;   
        $this->horaEntrada = $planilla->get()[0][$valorE];
        $this->horaSalida = $planilla->get()[0][$valorS];   
        return $valor_a_retornar;
     
       }else{
    $planillaNoche = \App\TurnoNoche::where('id_trabajador', $id)
                    ->where('mes', $this->mes)
                    ->where('anio', $this->anio);

                if($planillaNoche->count()>0){
                    $this->horarioNoche = $planillaNoche->get();
                    return 1;
                }else{
                    return 'No tiene horario';
                }                    

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
        ->where('turnoExtra', null)
		->where('fecha', $this->fecha)
		->where('tipo_movimiento', $movimiento);

       if($planilla->count()>0){
       	return $planilla->get()[0]['hora'];
       }else{
       	return 0;  // Si entrega 0 ; quiere decir que no hay movimiento, o entrada o salida.
       }

    } // Fin función VerificaMovimiento



    private function VerificaMovimientoTurnosExtras($id, $movimiento){

    $planilla = \App\asistencia::where('id_trabajador', $id)
        ->where('turnoExtra', 1)
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
         ->where('turnoExtra', null)
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

        $postListo = json_decode($post['locacion'], true);
      
        $planilla = \App\ingreso_empleados::where('id', $post['id'])->get();   

        $sucursales = \App\sucursales::where('id', $post['Sucursal'])->get();

        $diferenciaMetros = $this->distance($sucursales[0]['latitud'], $sucursales[0]['longitud'], $postListo['coords']['latitude'], $postListo['coords']['longitude'], 'K');       
       
        if($post['movimiento'] == 'entrada'){
         
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
            $tabla_asistencia->tiempo = time();
            $tabla_asistencia->locacion = $sucursales[0]['nombre'];
            $tabla_asistencia->latitude = $postListo['coords']['latitude'];
            $tabla_asistencia->longitude =$postListo['coords']['longitude'];
            $tabla_asistencia->altitude = $postListo['coords']['altitude'];
            $tabla_asistencia->url = $post['url'];
            $tabla_asistencia->distancia = $diferenciaMetros;
            $tabla_asistencia->sucursal = $sucursales[0]['id'];
            $tabla_asistencia->biometrica = $post['biometrica'];
             $tabla_asistencia->aprobado =( $post['biometrica'] > 0.61 ) ? 0 : 1 ;
            $tabla_asistencia->save();
                  echo json_encode(array('estatus' => 'EntradaRealizada', 'id'=>$tabla_asistencia->id, 'sucursal'=> $tabla_asistencia->locacion, 'nombre' => $tabla_asistencia->nombre , 'apellido'=> $tabla_asistencia->apellido, 'email'=> $planilla[0]['email1']));
        }
        
         if($post['movimiento'] == 'salida'){
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
            $tabla_asistencia->tiempo = time();
            $tabla_asistencia->locacion = $sucursales[0]['nombre'];
            $tabla_asistencia->latitude = $postListo['coords']['latitude'];
            $tabla_asistencia->longitude =$postListo['coords']['longitude'];
            $tabla_asistencia->altitude = $postListo['coords']['altitude'];
            $tabla_asistencia->url = $post['url'];
            $tabla_asistencia->distancia = $diferenciaMetros;
            $tabla_asistencia->sucursal = $sucursales[0]['id'];
            $tabla_asistencia->biometrica = $post['biometrica'];
            $tabla_asistencia->aprobado =( $post['biometrica'] > 0.61 ) ? 0 : 1 ;
            $tabla_asistencia->save();
                           echo json_encode(array('estatus' => 'SalidaRealizada', 'id'=>$tabla_asistencia->id, 'sucursal'=> $tabla_asistencia->locacion, 'nombre' => $tabla_asistencia->nombre , 'apellido'=> $tabla_asistencia->apellido, 'email'=> $planilla[0]['email1']));

        }
         // echo json_encode($Salida);
        //echo json_encode($this->fecha);
     

    } // Fin función SituacionMarcajeActual

     public function MarcarMovimientoApp(Request $request){
          
        $tabla_asistencia = new \App\asistencia;
      
        $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
      
        $planilla = \App\ingreso_empleados::where('id', $post['id'])->get();   

        $sucursales = \App\sucursales::where('id', $post['Sucursal'])->get();

        $diferenciaMetros = $this->distance($sucursales[0]['latitud'], $sucursales[0]['longitud'], $post['locacion']['coords']['latitude'], $post['locacion']['coords']['longitude'], 'K');       
       
        if($post['movimiento'] == 'entrada'){
         
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
            $tabla_asistencia->tiempo = time();
            $tabla_asistencia->locacion = $sucursales[0]['nombre'];
            $tabla_asistencia->latitude = $post['locacion']['coords']['latitude'];
            $tabla_asistencia->longitude = $post['locacion']['coords']['longitude'];
            $tabla_asistencia->altitude = $post['locacion']['coords']['altitude'];
            $tabla_asistencia->url = $post['url'];
            $tabla_asistencia->distancia = $diferenciaMetros;
            $tabla_asistencia->save();
            echo json_encode('EntradaRealizada');
        }
        
         if($post['movimiento'] == 'salida'){
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
            $tabla_asistencia->tiempo = time();
            $tabla_asistencia->locacion = $sucursales[0]['nombre'];
            $tabla_asistencia->latitude = $post['locacion']['coords']['latitude'];
            $tabla_asistencia->longitude = $post['locacion']['coords']['longitude'];
            $tabla_asistencia->altitude = $post['locacion']['coords']['altitude'];
            $tabla_asistencia->url = $post['url'];
            $tabla_asistencia->distancia = $diferenciaMetros;
            $tabla_asistencia->save();
            echo json_encode('SalidaRealizada');

        }
         // echo json_encode($Salida);
        //echo json_encode($this->fecha);

    } // Fin función SituacionMarcajeActual


    public function MarcarMovimientoRespaldo(Request $request){
        $tabla_asistencia = new \App\asistencia;
	      $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
      
        $planilla = \App\ingreso_empleados::where('id', $post['id'])->get();
      
  
        //$turnos = \App\turnos::where([ ['trabajador_id', $post['id']], ['mes',  $this->mes], ['anio', $this->anio] ]);
          
        
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
          $planilla = \App\turnosVariables::where('trabajador_id', $id)
          ->where('mes', $this->mes)
          ->where('anio', $this->anio);

          $dia_e = $this->dia_e;
          $dia_s = $this->dia_s;
    }else{
         $planilla = \App\turnosFijos::where('trabajador_id', $id);
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




        public function distance($lat1, $lon1, $lat2, $lon2, $unit) {
          if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
          }
          else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
              return ($miles * 1.609344);
            } else if ($unit == "N") {
              return ($miles * 0.8684);
            } else {
              return $miles;
            }
          }
        }


} // Fin Clase MarcajeController
