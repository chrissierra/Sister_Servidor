<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class marcajeTrabajadoresSinTurnoEstablecido extends Controller
{
        function __construct(){

		        date_default_timezone_set('America/Santiago');
		        	$this->tiempo = time();
		    		  $this->mes = date('m')*1;
		    		  $this->anio = date('Y');
			        $this->dia_e = (date('d') *1) . 'e';
			        $this->dia_s = (date('d') *1) . 's';
			        $this->fecha = date('d/m/Y');
              $this->turnoExtraEnCurso = 0;
              $this->cuantia_actual = (date('H') + ( date('i') /60 ));
    	} // Fin función __construct


    public function VerificarUltimoMovimiento(Request $request){
	      		
	      		$post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]

	      		$ultimoMovimiento = \App\asistencia::where('turnoExtra', null)
                ->where('id_trabajador', $post['id'])
               ->orderBy('id', 'desc')
               ->take(1);
               
             // echo json_encode($ultimoMovimiento->get());
             if($ultimoMovimiento->count() === 0 ){
              	# Entrada
              	echo json_encode('entrada');
              }elseif(($ultimoMovimiento->count() > 0) && ($ultimoMovimiento->get()[0]['tipo_movimiento']=== 'salida') && (($this->tiempo- $ultimoMovimiento->get()[0]['tiempo']) <28800)){
              	echo json_encode(array("estatus" => 'Listo', "diferenciaTimes" => $this->diferenciaTimes($ultimoMovimiento->get()[0]['tiempo'])));
              }elseif(($ultimoMovimiento->count() > 0) && ($ultimoMovimiento->get()[0]['tipo_movimiento'] === 'entrada')){
              	return $this->analizarMovimiento($ultimoMovimiento->get()[0]);
              }elseif(($ultimoMovimiento->count() > 0) && ($ultimoMovimiento->get()[0]['tipo_movimiento']=== 'salida') && (($this->tiempo- $ultimoMovimiento->get()[0]['tiempo'])>28800)){
              	echo json_encode('entrada');
              }
    }


  private function diferenciaTimes($ultimoRegistroTiempo){

    $whole = (int) $ultimoRegistroTiempo;  // 5
    $frac  = $ultimoRegistroTiempo - (int) $ultimoRegistroTiempo;  // .7
    return ((($this->tiempo -  $ultimoRegistroTiempo )/ 60) / 60);
  }


  public function VerificarUltimoMovimientoTurnoExtra(Request $request){
                
                $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]

                $ultimoMovimiento = \App\asistencia::where('id_trabajador', $post['id'])
                ->where('turnoExtra', 1)
               ->orderBy('id', 'desc')
               ->take(1);
               
              if($ultimoMovimiento->count() === 0 ){
                # Entrada
                echo json_encode('entrada');
              }elseif($ultimoMovimiento->count() > 0 && $ultimoMovimiento->get()[0]['tipo_movimiento']=== 'salida' && ($this->tiempo- $ultimoMovimiento->get()[0]['tiempo']) <28800){
                echo json_encode('entrada'); // Quiza pueda poner otro turno, luego del listo. Son turnos extras... ****// 25-02-2019 : Entrego entrada , para repetir turno. Caso del famoso claudiomuñoñz.
              }elseif($ultimoMovimiento->count() > 0 && $ultimoMovimiento->get()[0]['tipo_movimiento'] === 'entrada'){
                return $this->analizarMovimiento($ultimoMovimiento->get()[0]);
              }elseif($ultimoMovimiento->count() > 0 && $ultimoMovimiento->get()[0]['tipo_movimiento']=== 'salida' && ($this->tiempo- $ultimoMovimiento->get()[0]['tiempo'])>=28800){
                echo json_encode('entrada');
              }
    }




      private function VerificarUltimoMovimientoTurnoExtraPorParametroInterna($id){
                
                

                $ultimoMovimiento = \App\asistencia::where('id_trabajador', $id)
                ->where('turnoExtra', 1)
               ->orderBy('id', 'desc')
               ->take(1);
               
              if($ultimoMovimiento->count() === 0 ){
                # Entrada
               return $this->turnoExtraEnCurso =0;
              }elseif($ultimoMovimiento->count() > 0 && $ultimoMovimiento->get()[0]['tipo_movimiento']=== 'salida'){
               return  $this->turnoExtraEnCurso =0;
              }elseif($ultimoMovimiento->count() > 0 && $ultimoMovimiento->get()[0]['tipo_movimiento'] === 'entrada'){
                return  $this->turnoExtraEnCurso =1;
              }elseif($ultimoMovimiento->count() > 0 && $ultimoMovimiento->get()[0]['tipo_movimiento']=== 'entrada' && ($this->tiempo- $ultimoMovimiento->get()[0]['tiempo'])>43200){
               return  $this->turnoExtraEnCurso =0; // Se acabó por tiempo el turno extra, por lógica puede marcar turno, no está en turno extra. 
               // Por error de no marcar salida de turno extra
              }
    }

    private function analizarMovimientoTurnosExtras($objetoUltimoMov){
        if($this->tiempo-$objetoUltimoMov['tiempo']>52800){
            #mas de 14.5 horas del ultimo movimiento entrada. Entrega entrada nuevamente. // IMPORTANTE: Cambiado el 11.02.2019 ; Antes eran 12 horas . 
            echo json_encode('entrada');
        }elseif($this->tiempo-$objetoUltimoMov['tiempo']<52800){
            #Entrega a marcar la salida, han pasado menos de 14.5 horas
            echo json_encode('salida');

        }
    }



    private function analizarMovimiento($objetoUltimoMov){
    	if($this->tiempo-$objetoUltimoMov['tiempo']>52800){
    		#mas de 14.5 horas del ultimo movimiento entrada. Entrega entrada nuevamente. 
    		echo json_encode('entrada');
    	}elseif($this->tiempo-$objetoUltimoMov['tiempo']<52800){
    		#Entrega a marcar la salida, han pasado menos de 14.5 horas
    		echo json_encode('salida');

    	}
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


     public function MarcarMovimiento(Request $request){
          
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
            $tabla_asistencia->status_entrada = '';  // Atraso o no
            $tabla_asistencia->status_salida = '';  // Atraso o no
            $tabla_asistencia->cuantia_entrada = date('H')+ (date('i') /60 ) ;
            $tabla_asistencia->cuantia_salida = '';
            $tabla_asistencia->mes = date('m');
            $tabla_asistencia->anio = date('Y');
            $tabla_asistencia->dia = date('d');
            $tabla_asistencia->cuantia_esperada = '';
            $tabla_asistencia->cuantia_diferencia_real_esperada = '';
            $tabla_asistencia->tiempo = time();
            $tabla_asistencia->locacion = $sucursales[0]['nombre'];
            $tabla_asistencia->latitude = $post['locacion']['coords']['latitude'];
            $tabla_asistencia->longitude = $post['locacion']['coords']['longitude'];
            $tabla_asistencia->altitude = $post['locacion']['coords']['altitude'];
            $tabla_asistencia->url = $post['url'];
            $tabla_asistencia->distancia = $diferenciaMetros;
            $tabla_asistencia->sucursal = $sucursales[0]['id'];
            $tabla_asistencia->biometrica = $post['biometrica'];
            $tabla_asistencia->aprobado =( $post['biometrica'] > 0.61 ) ? 0 : 1 ;
            $tabla_asistencia->save();
                           echo json_encode(array('estatus' => 'EntradaRealizada', 'id'=>$tabla_asistencia->id, 'sucursal'=> $tabla_asistencia->locacion, 'nombre' => $tabla_asistencia->nombre , 'apellido'=> $tabla_asistencia->apellido, 'email'=> $planilla[0]['email1'] ));
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
            $tabla_asistencia->status_salida = '' ;  // Atraso o no
            $tabla_asistencia->cuantia_entrada = '' ;
            $tabla_asistencia->cuantia_salida = date('H') + ( date('i') /60 );
            $tabla_asistencia->mes = date('m');
            $tabla_asistencia->anio = date('Y');
            $tabla_asistencia->dia = date('d');
            $tabla_asistencia->cuantia_esperada = '';
            $tabla_asistencia->cuantia_diferencia_real_esperada = '';
            $tabla_asistencia->tiempo = time();
            $tabla_asistencia->locacion = $sucursales[0]['nombre'];
            $tabla_asistencia->latitude = $post['locacion']['coords']['latitude'];
            $tabla_asistencia->longitude = $post['locacion']['coords']['longitude'];
            $tabla_asistencia->altitude = $post['locacion']['coords']['altitude'];
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




     public function MarcarMovimientoTurnoExtra(Request $request){
          
        $tabla_asistencia = new \App\asistencia;
        
          $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
      
        $planilla = \App\ingreso_empleados::where('id', $post['id'])->get();   
        
        $postListo = json_decode($post['locacion'], true);
        
        $sucursales = \App\sucursales::where('id', $post['Sucursal'])->get();

        $diferenciaMetros = $this->distance($sucursales[0]['latitud'], $sucursales[0]['longitud'], $postListo['coords']['latitude'], $postListo['coords']['longitude'], 'K');       

      //  $post['biometrica'] > 0.61 ? $this->notificaTurnoReprobable($planilla[0]['nombre_empresa_usuario_plataforma'],$planilla[0]['nombre'], $planilla[0]['apellido']) : 1 ;

      // $diferenciaMetros > 0.3  ? $this->notificaTurnoReprobable($planilla[0]['nombre_empresa_usuario_plataforma'],$planilla[0]['nombre'], $planilla[0]['apellido']) : 1 ;
       
        if($post['movimiento'] == 'entrada'){
         
            $tabla_asistencia->rut = $planilla[0]['rut'];
            $tabla_asistencia->id_trabajador  = $planilla[0]['id'];
            $tabla_asistencia->tipo_movimiento  = 'entrada';
            $tabla_asistencia->fecha = date('d/m/Y');
            $tabla_asistencia->hora = date('H:i:s');
            $tabla_asistencia->usuario_cliente = $planilla[0]['nombre_empresa_usuario_plataforma'];
            $tabla_asistencia->nombre = $planilla[0]['nombre'];
            $tabla_asistencia->apellido = $planilla[0]['apellido'];
            $tabla_asistencia->status_entrada = '';  // Atraso o no
            $tabla_asistencia->status_salida = '';  // Atraso o no
            $tabla_asistencia->cuantia_entrada = date('H')+ (date('i') /60 ) ;
            $tabla_asistencia->cuantia_salida = '';
            $tabla_asistencia->mes = date('m');
            $tabla_asistencia->anio = date('Y');
            $tabla_asistencia->dia = date('d');
            $tabla_asistencia->cuantia_esperada = '';
            $tabla_asistencia->cuantia_diferencia_real_esperada = '';
            $tabla_asistencia->tiempo = time();
            $tabla_asistencia->locacion = $sucursales[0]['nombre'];
            $tabla_asistencia->latitude = $postListo['coords']['latitude'];
            $tabla_asistencia->longitude = $postListo['coords']['longitude'];
            $tabla_asistencia->altitude = $postListo['coords']['altitude'];
            $tabla_asistencia->url = $post['url'];
            $tabla_asistencia->distancia = $diferenciaMetros;
            $tabla_asistencia->turnoExtra = 1;
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
            $tabla_asistencia->status_salida = '' ;  // Atraso o no
            $tabla_asistencia->cuantia_entrada = '' ;
            $tabla_asistencia->cuantia_salida = date('H') + ( date('i') /60 );
            $tabla_asistencia->mes = date('m');
            $tabla_asistencia->anio = date('Y');
            $tabla_asistencia->dia = date('d');
            $tabla_asistencia->cuantia_esperada = '';
            $tabla_asistencia->cuantia_diferencia_real_esperada = '';
            $tabla_asistencia->tiempo = time();
            $tabla_asistencia->locacion = $sucursales[0]['nombre'];
            $tabla_asistencia->latitude = $postListo['coords']['latitude'];
            $tabla_asistencia->longitude = $postListo['coords']['longitude'];
            $tabla_asistencia->altitude = $postListo['coords']['altitude'];
            $tabla_asistencia->url = $post['url'];
            $tabla_asistencia->distancia = $diferenciaMetros;
            $tabla_asistencia->turnoExtra = 1;
            $tabla_asistencia->sucursal = $sucursales[0]['id'];
            $tabla_asistencia->biometrica = $post['biometrica'];
            $tabla_asistencia->aprobado =( $post['biometrica'] > 0.61 ) ? 0 : 1 ;
            $tabla_asistencia->save();
                       echo json_encode(array('estatus' => 'SalidaRealizada', 'id'=>$tabla_asistencia->id, 'sucursal'=> $tabla_asistencia->locacion, 'nombre' => $tabla_asistencia->nombre , 'apellido'=> $tabla_asistencia->apellido, 'email'=> $planilla[0]['email1']));

        }
         // echo json_encode($Salida);
        //echo json_encode($this->fecha);

    } // Fin función SituacionMarcajeActual


     public function MarcarMovimientoWeb(Request $request){
          
        $tabla_asistencia = new \App\asistencia;
      
        $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]

        $postListo = json_decode($post['locacion'], true);
      
        $planilla = \App\ingreso_empleados::where('id', $post['id'])->get();   

        $sucursales = \App\sucursales::where('id', $post['Sucursal'])->get();

        $diferenciaMetros = $this->distance($sucursales[0]['latitud'], $sucursales[0]['longitud'], $postListo['coords']['latitude'], $postListo['coords']['longitude'], 'K');       
       
     //  $post['biometrica'] > 0.61 ? $this->notificaTurnoReprobable($planilla[0]['nombre_empresa_usuario_plataforma'],$planilla[0]['nombre'], $planilla[0]['apellido']) : 1 ;

     //  $diferenciaMetros > 0.3  ? $this->notificaTurnoReprobable($planilla[0]['nombre_empresa_usuario_plataforma'],$planilla[0]['nombre'], $planilla[0]['apellido']) : 1 ;
        
        if($post['movimiento'] == 'entrada'){
         
            $tabla_asistencia->rut = $planilla[0]['rut'];
            $tabla_asistencia->id_trabajador  = $planilla[0]['id'];
            $tabla_asistencia->tipo_movimiento  = 'entrada';
            $tabla_asistencia->fecha = date('d/m/Y');
            $tabla_asistencia->hora = date('H:i:s');
            $tabla_asistencia->usuario_cliente = $planilla[0]['nombre_empresa_usuario_plataforma'];
            $tabla_asistencia->nombre = $planilla[0]['nombre'];
            $tabla_asistencia->apellido = $planilla[0]['apellido'];
            $tabla_asistencia->status_entrada = '';  // Atraso o no
            $tabla_asistencia->status_salida = '';  // Atraso o no
            $tabla_asistencia->cuantia_entrada = date('H')+ (date('i') /60 ) ;
            $tabla_asistencia->cuantia_salida = '';
            $tabla_asistencia->mes = date('m');
            $tabla_asistencia->anio = date('Y');
            $tabla_asistencia->dia = date('d');
            $tabla_asistencia->cuantia_esperada = '';
            $tabla_asistencia->cuantia_diferencia_real_esperada = '';
            $tabla_asistencia->tiempo = time();
            $tabla_asistencia->locacion = $sucursales[0]['nombre'];
            $tabla_asistencia->latitude = $postListo['coords']['latitude'];
            $tabla_asistencia->longitude = $postListo['coords']['longitude'];
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
            $tabla_asistencia->status_salida = '' ;  // Atraso o no
            $tabla_asistencia->cuantia_entrada = '' ;
            $tabla_asistencia->cuantia_salida = date('H') + ( date('i') /60 );
            $tabla_asistencia->mes = date('m');
            $tabla_asistencia->anio = date('Y');
            $tabla_asistencia->dia = date('d');
            $tabla_asistencia->cuantia_esperada = '';
            $tabla_asistencia->cuantia_diferencia_real_esperada = '';
            $tabla_asistencia->tiempo = time();
            $tabla_asistencia->locacion = $sucursales[0]['nombre'];
            $tabla_asistencia->latitude = $postListo['coords']['latitude'];
            $tabla_asistencia->longitude = $postListo['coords']['longitude'];
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


    private function notificaTurnoReprobable($id_empresa, $nombre, $apellido){
      /*  $planilla = \App\ingreso_empleados::where('nombre_empresa_usuario_plataforma', $id_empresa)->get(); 
        foreach ($planilla as $key => $value) {
            # code...
            if($value["onesignal"] !== null){
                $this->sendMessage($value["onesignal"], $nombre, $apellido);
            }
        }*/
    }


    private function getDiferenciaCuantias($cuantia_actual, $cuantiaEsperada, $movimiento){

      if($movimiento == 'entrada') // 831 - 830
        return $cuantiaEsperada - $cuantia_actual;

      if($movimiento == 'salida')  // Salir antes de la hora 1831-1830
        return $cuantia_actual - $cuantiaEsperada;

    }







 public function MarcarMovimientoWebNoches(Request $request){
          
        $tabla_asistencia = new \App\asistencia;
      
        $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]

        $postListo = json_decode($post['locacion'], true);
      
        $planilla = \App\ingreso_empleados::where('id', $post['id'])->get();   

        $sucursales = \App\sucursales::where('id', $post['Sucursal'])->get();

        $diferenciaMetros = $this->distance($sucursales[0]['latitud'], $sucursales[0]['longitud'], $postListo['coords']['latitude'], $postListo['coords']['longitude'], 'K');       
        // cuantia_actual = 
        $cuantiaEsperada = explode( ':', $post['hora_esperada'])[0] + (explode(':', $post['hora_esperada'])[1] / 60 );
       
     //   $post['biometrica'] > 0.61 ? $this->notificaTurnoReprobable($planilla[0]['nombre_empresa_usuario_plataforma'],$planilla[0]['nombre'], $planilla[0]['apellido']) : 1 ;

      // $diferenciaMetros > 0.3  ? $this->notificaTurnoReprobable($planilla[0]['nombre_empresa_usuario_plataforma'],$planilla[0]['nombre'], $planilla[0]['apellido']) : 1 ;

        if($post['movimiento'] == 'entrada'){
         
            $tabla_asistencia->rut = $planilla[0]['rut'];
            $tabla_asistencia->id_trabajador  = $planilla[0]['id'];
            $tabla_asistencia->tipo_movimiento  = 'entrada';
            $tabla_asistencia->fecha = date('d/m/Y');
            $tabla_asistencia->hora = date('H:i:s');
            $tabla_asistencia->usuario_cliente = $planilla[0]['nombre_empresa_usuario_plataforma'];
            $tabla_asistencia->nombre = $planilla[0]['nombre'];
            $tabla_asistencia->apellido = $planilla[0]['apellido'];
            $tabla_asistencia->status_entrada = $this->getDiferenciaCuantias($this->cuantia_actual , $cuantiaEsperada, 'entrada') < 0 ? 'Atraso' : 'ok' ;  // Atraso o no
            $tabla_asistencia->status_salida = '';  // Atraso o no
            $tabla_asistencia->cuantia_entrada = date('H')+ (date('i') /60 ) ;
            $tabla_asistencia->cuantia_salida = '';
            $tabla_asistencia->mes = date('m');
            $tabla_asistencia->anio = date('Y');
            $tabla_asistencia->dia = date('d');
            $tabla_asistencia->cuantia_esperada =  $cuantiaEsperada;
            $tabla_asistencia->cuantia_diferencia_real_esperada = $this->getDiferenciaCuantias($this->cuantia_actual , $cuantiaEsperada, 'entrada');
            $tabla_asistencia->tiempo = time();
            $tabla_asistencia->locacion = $sucursales[0]['nombre'];
            $tabla_asistencia->latitude = $postListo['coords']['latitude'];
            $tabla_asistencia->longitude = $postListo['coords']['longitude'];
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
            $tabla_asistencia->status_salida = $this->getDiferenciaCuantias($this->cuantia_actual , $cuantiaEsperada, 'salida') < 0 ? 'MenosHoras' : 'HoraExtra' ;  // Atraso o no
            $tabla_asistencia->cuantia_entrada = '' ;
            $tabla_asistencia->cuantia_salida = date('H') + ( date('i') /60 );
            $tabla_asistencia->mes = date('m');
            $tabla_asistencia->anio = date('Y');
            $tabla_asistencia->dia = date('d');
            $tabla_asistencia->cuantia_esperada =  $cuantiaEsperada;
            $tabla_asistencia->cuantia_diferencia_real_esperada = $this->getDiferenciaCuantias($this->cuantia_actual , $cuantiaEsperada, 'salida');
            $tabla_asistencia->tiempo = time();
            $tabla_asistencia->locacion = $sucursales[0]['nombre'];
            $tabla_asistencia->latitude = $postListo['coords']['latitude'];
            $tabla_asistencia->longitude = $postListo['coords']['longitude'];
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







    private function sendMessage($id, $nombre, $apellido) {
    /*$content      = array(
        "en" => 'Marcaje dudoso de ' . $nombre . " " . $apellido
    );

    $fields = array(
        'app_id' => "5200c8b2-a266-4832-9c92-47ea8616fb08",
        'include_player_ids' => [$id],
       // 'included_segments' => array(
        //    'All'
        //),
        'data' => array(
            "foo" => "bar"
        ),
        'contents' => $content
       // 'web_buttons' => $hashes_array
    );

    $fields = json_encode($fields);


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic N2IwZGNkYTktNTBlZS00MWRhLWE3YmQtYmQ1MzVkZjQ2YjVm'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response; */
}




}
