<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class asistencia_offline_controller extends Controller
{
    //
	
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
          
        $tabla_asistencia = new \App\asistencia_offline;
	    
	    $post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]
      
        $planilla = \App\ingreso_empleados::where('id', $post['id_trabajador'])->get();   
		$postListo = json_decode($post['locacion'], true);
        $sucursales = \App\sucursales::where('id', $post['Sucursal'])->get();
      	var_dump($post['urlEscrita']);
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
            $tabla_asistencia->cuantia_entrada = date('H')+ (date('i') /60 ) ;
            $tabla_asistencia->cuantia_salida = '';
            $tabla_asistencia->mes = date('m');
            $tabla_asistencia->anio = date('Y');
            $tabla_asistencia->dia = date('d');
            $tabla_asistencia->tiempo = time();
            $tabla_asistencia->locacion = $sucursales[0]['nombre'];
            $tabla_asistencia->latitude = $postListo['coords']['latitude'];
            $tabla_asistencia->longitude = $postListo['coords']['longitude'];

            $tabla_asistencia->url = $post['urlEscrita'];
            $tabla_asistencia->distancia = $diferenciaMetros;
            $tabla_asistencia->sucursal = $sucursales[0]['id'];

            $tabla_asistencia->save();
                           echo json_encode(array('estatus' => 'EntradaRealizada', 'id'=>$tabla_asistencia->id, 'sucursal'=> $tabla_asistencia->locacion, 'nombre' => $tabla_asistencia->nombre , 'apellido'=> $tabla_asistencia->apellido));
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
            $tabla_asistencia->cuantia_entrada = '' ;
            $tabla_asistencia->cuantia_salida = date('H') + ( date('i') /60 );
            $tabla_asistencia->mes = date('m');
            $tabla_asistencia->anio = date('Y');
            $tabla_asistencia->dia = date('d');

            $tabla_asistencia->tiempo = time();
            $tabla_asistencia->locacion = $sucursales[0]['nombre'];

            $tabla_asistencia->longitude = $postListo['coords']['longitude'];
            $tabla_asistencia->altitude = $postListo['coords']['altitude'];
            $tabla_asistencia->url = $post['urlEscrita'];
            $tabla_asistencia->distancia = $diferenciaMetros;
            $tabla_asistencia->sucursal = $sucursales[0]['id'];

            $tabla_asistencia->save();
                           echo json_encode(array('estatus' => 'SalidaRealizada', 'id'=>$tabla_asistencia->id, 'sucursal'=> $tabla_asistencia->locacion, 'nombre' => $tabla_asistencia->nombre , 'apellido'=> $tabla_asistencia->apellido));

        }
         // echo json_encode($Salida);
        //echo json_encode($this->fecha);

    } // Fin función SituacionMarcajeActual





}
