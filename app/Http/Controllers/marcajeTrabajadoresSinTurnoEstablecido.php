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
    	} // Fin función __construct


    public function VerificarUltimoMovimiento(Request $request){
	      		
	      		$post = $request->json()->all(); // Se ingresa como array EJ: $post["algo"]

	      		$ultimoMovimiento = \App\asistencia::where('id_trabajador', $post['id'])
               ->orderBy('id', 'desc')
               ->take(1);
               
              if($ultimoMovimiento->count() === 0 ){
              	# Entrada
              	echo json_encode('entrada');
              }elseif($ultimoMovimiento->count() > 0 && $ultimoMovimiento->get()[0]['tipo_movimiento']=== 'salida' && ($this->tiempo- $ultimoMovimiento->get()[0]['tiempo']) <43200){
              	echo json_encode('Listo');
              }elseif($ultimoMovimiento->count() > 0 && $ultimoMovimiento->get()[0]['tipo_movimiento'] === 'entrada'){
              	return $this->analizarMovimiento($ultimoMovimiento->get()[0]);
              }elseif($ultimoMovimiento->count() > 0 && $ultimoMovimiento->get()[0]['tipo_movimiento']=== 'salida' && ($this->tiempo- $ultimoMovimiento->get()[0]['tiempo'])>43200){
              	echo json_encode('entrada');
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
            $tabla_asistencia->status_salida = '' ;  // Atraso o no
            $tabla_asistencia->cuantia_entrada = '' ;
            $tabla_asistencia->cuantia_salida = date('H') + ( date('i') /60 );
            $tabla_asistencia->mes = date('m');
            $tabla_asistencia->anio = date('Y');
            $tabla_asistencia->dia = date('d');
            $tabla_asistencia->cuantia_esperada = '';
            $tabla_asistencia->cuantia_diferencia_real_esperada = '';
            $tabla_asistencia->tiempo = time();
            $tabla_asistencia->locacion = $post['Sucursal'];
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








}
