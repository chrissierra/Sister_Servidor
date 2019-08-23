<?php
namespace App\Clases;
use Illuminate\Http\Request;
class DiasTrabajados {

	public function DiasTrabajadosPorTrabajador($id, $mes, $anio){

		$TurnoNoche = \App\TurnoNoche::where('id_trabajador', $id)
	                    ->where('mes', $mes)
	                    ->where('anio', $anio);
        
        $TurnosFijos =  \App\turnosFijos::where('trabajador_id', $id);

        $TurnosVariables = \App\Turnos::where('trabajador_id', $id)
          ->where('mes', $mes)
          ->where('anio', $anio);	          

        if($TurnoNoche->count()>0){            
            return $this->Trabajador_Con_HorarioNoche($id, $mes, $anio, $TurnoNoche);
        }elseif ($TurnosFijos->count()>0) {
            return $this->Trabajador_Con_HorarioFijo($id, $mes, $anio, $TurnosFijos);
        }elseif ($TurnosVariables->count()>0) {
            return $this->Trabajador_Con_HorarioVariable($id, $mes, $anio, $TurnosVariables);
        }  
	
	} // Fin Función SeleccionarTipoTurno



	 /**
		 @params $idTrabajador, 
	 
	 */
   private function Trabajador_Sin_Horario($id, $mes, $anio, $modelo) {
        
        $variable1 = json_decode ($modelo->get());
        var_dump($variable1);
    }
 
   private function Trabajador_Con_HorarioFijo($id, $mes, $anio, $modelo) {
        $variable1 = json_decode ($modelo->get(), true);
       // $variable2 = json_decode($variable1[0], true);
        $array =  (array) $variable1;
		$d=cal_days_in_month(CAL_GREGORIAN,$mes,$anio);	
		for ($i=0; $i < $d; $i++) { 
			# code...
			$fecha = new DateTime($anio."-".$mes."-".$dia);
			echo $fecha->format('w');
		}
        echo "Lunes Entrada " . $array[0]['0e'];
        echo "Lunes Salida " . $array[0]['0s'];
        echo "Martes Entrada " . $array[0]['1e'];
        echo "Martes Salida " . $array[0]['1s'];
        echo "Miecoles Entrada " . $array[0]['2e'];
        echo "Miecoles Salida " . $array[0]['2s'];
        echo "Jueves Entrada " . $array[0]['3e'];
        echo "Jueves Salida " . $array[0]['3s'];
        echo "Viernes Entrada " . $array[0]['4e'];
        echo "Viernes Salida " . $array[0]['4s'];
        echo "Sabado Entrada " . $array[0]['5e'];
        echo "Sabado Salida " . $array[0]['5s'];
        echo "Domingo Entrada " . $array[0]['6e'];
        echo "Domingo Salida " . $array[0]['6s'];



    }



   private function Trabajador_Con_HorarioVariable($id, $mes, $anio, $modelo) {
        $variable1 = json_decode ($modelo->get());
        var_dump($variable1);
    }
   

   private function Trabajador_Con_HorarioNoche($id, $mes, $anio, $modelo) {
        $variable1 = json_decode ($modelo->get());
        $variable2 = json_decode($variable1[0]->turno);
        $array =  (array) $variable2;
        //echo "Count -> " . count($array);
        $dt_contador = 0;
        $dt = 0;
        for ($i=1; $i < count($array); $i++) { 
        	# code...
        	$contador = 0;
        	if(isset( $array["tipo_a_".$i])){

	        		echo "Fecha Día $i-$mes-$anio ;  Numero " . $i . " -> " . strtolower ($array["tipo_a_".$i]) . " Y la hora es " . $array["hora_a_".$i] . "<br>";

		        	$str = strtolower ($array["tipo_a_".$i]);
					preg_match_all('!\d+!', $str, $matches);
					//print_r($matches);



					if(strtolower ($array["tipo_a_".$i]) =='entrada' || strtolower ($array["tipo_a_".$i]) == 'salida'){

								$ultimoMovimiento = \App\asistencia::where('turnoExtra', null)
							       ->where('id_trabajador', $id)
							       ->where('mes',  $mes)
							       ->where('anio',  $anio)
							       ->where('dia',  $i)
							       ->where('tipo_movimiento',  strtolower($array["tipo_a_".$i]));
				                 

				                if(isset($ultimoMovimiento) && $ultimoMovimiento->count()>0){
				                	echo "Si Trabajó...". $ultimoMovimiento->get()[0]->hora  . "<br>";
				                	if(strtolower($array["tipo_a_".$i]) == 'entrada') $dt_contador=1;
				                	if(strtolower($array["tipo_a_".$i]) == 'salida' && $dt_contador == 1){
				                    $dt_contador = 0;

				                	$dt++;	
				                	} 
				                }else{
				                	$dt_contador = 0;
				                	echo "falto...<br>";
				                }

				    }
			}









        	if(isset( $array["tipo_b_".$i])){
        		echo "Fecha Día $i-$mes-$anio ;  Numero " . $i . " -> " . strtolower ($array["tipo_b_".$i]). " Y la hora es " . $array["hora_b_".$i] . "<br>" ;


        	$str = strtolower ($array["tipo_b_".$i]);
			preg_match_all('!\d+!', $str, $matches);
			//print_r($matches);
				if(strtolower ($array["tipo_b_".$i]) =='entrada' || strtolower ($array["tipo_b_".$i]) == 'salida'){
							$ultimoMovimiento = \App\asistencia::where('turnoExtra', null)
							 ->where('id_trabajador', $id)
							 ->where('mes',  $mes)
							 ->where('anio',  $anio)
							 ->where('dia',  $i)
							 ->where('tipo_movimiento',  strtolower($array["tipo_b_".$i]));
													                



							 if(isset($ultimoMovimiento) && $ultimoMovimiento->count()>0){
							 	echo "Si Trabajó...". $ultimoMovimiento->get()[0]->hora . "<br>";
							 	if(strtolower($array["tipo_b_".$i]) == 'entrada') $dt_contador=1;
				                	if(strtolower($array["tipo_b_".$i]) == 'salida' && $dt_contador == 1){
				                    $dt_contador = 0;
				                	$dt++;	
				                	} 
							 }else{
							 	$dt_contador = 0;
							 	echo "falto...<br>";
							 }
				   }
        	}



        }
        echo $dt;
    }   
 
}


/*
{"mes":"12","anio":"2018","id_trabajador":"95",
"hora_a_1":"",
"tipo_a_1":"",
"hora_b_1":"",
"tipo_b_1":"",
"hora_a_2":"",
"tipo_a_2":"",
"hora_b_2":"",
"tipo_b_2":"",
"hora_a_3":"",
"tipo_a_3":"",
"hora_b_3":"",
"tipo_b_3":"",
"hora_a_4":"",
"tipo_a_4":"",
"hora_b_4":"",
"tipo_b_4":"",
"hora_a_5":"",
"tipo_a_5":"",
"hora_b_5":"",
"tipo_b_5":"",
"hora_a_6":"",
"tipo_a_6":"",
"hora_b_6":"",
"tipo_b_6":"",
"hora_a_7":"",
"tipo_a_7":"",
"hora_b_7":"",
"tipo_b_7":"",
"hora_a_8":"",
"tipo_a_8":"",
"hora_b_8":"",
"tipo_b_8":"",
"hora_a_9":"",
"tipo_a_9":"",
"hora_b_9":"",
"tipo_b_9":"",
"hora_a_10":"",
"tipo_a_10":"","hora_b_10":"","tipo_b_10":"","hora_a_11":"","tipo_a_11":"","hora_b_11":"","tipo_b_11":"","hora_a_12":"","tipo_a_12":"","hora_b_12":"","tipo_b_12":"","hora_a_13":"","tipo_a_13":"","hora_b_13":"","tipo_b_13":"","hora_a_14":"","tipo_a_14":"","hora_b_14":"","tipo_b_14":"","hora_a_15":"","tipo_a_15":"","hora_b_15":"","tipo_b_15":"","hora_a_16":"","tipo_a_16":"","hora_b_16":"","tipo_b_16":"","hora_a_17":"","tipo_a_17":"","hora_b_17":"","tipo_b_17":"","hora_a_18":"","tipo_a_18":"","hora_b_18":"","tipo_b_18":"","hora_a_19":"","tipo_a_19":"","hora_b_19":"","tipo_b_19":"","hora_a_20":"","tipo_a_20":"","hora_b_20":"","tipo_b_20":"","hora_a_21":"","tipo_a_21":"","hora_b_21":"","tipo_b_21":"","hora_a_22":"","tipo_a_22":"","hora_b_22":"","tipo_b_22":"","hora_a_23":"","tipo_a_23":"","hora_b_23":"","tipo_b_23":"","hora_a_24":"","tipo_a_24":"","hora_b_24":"","tipo_b_24":"","hora_a_25":"","tipo_a_25":"","hora_b_25":"","tipo_b_25":"","hora_a_26":"","tipo_a_26":"","hora_b_26":"","tipo_b_26":"","hora_a_27":"","tipo_a_27":"","hora_b_27":"","tipo_b_27":"","hora_a_28":"","tipo_a_28":"","hora_b_28":"","tipo_b_28":"","hora_a_29":"","tipo_a_29":"","hora_b_29":"","tipo_b_29":"","hora_a_30":"","tipo_a_30":"","hora_b_30":"","tipo_b_30":"",
"hora_a_31":"09:30",
"tipo_a_31":"Entrada",
"hora_b_31":"13:30",
"tipo_b_31":"Salida"}

// 

1 - Recorrer el json ( turnos noche ). O la tabla. ( turnos dia sin noche)
2- ver cuales no son vacios; los que tienen algo. tomar ese numero. llevarlo a dia ( su Key, llevarla a día ) ( string.extraenumeros() : int ; ese int del turno contrastarlo en la tabla asistencia llevandolo a una query de mes consultado y dia; si hay algo ver ademas si cumple con entrada o salida ) . revisarlo en tabla asistencia. Ver si es entrada o salida. Si cumple con el movimiento y es movimiento === movimiento, ver cuántas horas de atraso podrían haber. 


*/