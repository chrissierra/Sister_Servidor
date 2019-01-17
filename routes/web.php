<?php
use Illuminate\Support\Facades\Storage;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test/{id}', function ($id) {
          date_default_timezone_set('America/Santiago');
        $this->mes = date('m')*1;
        $this->anio = date('Y');
        $this->dia = (date('d') *1) . 'e';
        $this->timeUnix = (date('d') *1) . 's';
        $this->fecha = date('d/m/Y');
     //echo public_path();
        $path = './' . $this->anio . '/' . $this->mes .'/' .$this->dia . '/' . $this->timeUnix . '/';

        Storage::makeDirectory($path);
});


Route::get('/perfil_trabajador/{id}', function ($id) {

     $planilla = \App\ingreso_empleados::where('id', $id)->get();

     echo $planilla->toJson();

});





Route::get('/estatusturnos/{id}/{mes}/{anio}', function ($id, $mes, $anio) {

     $turnos = \App\Turnos::where([ ['trabajador_id', $id], ['mes', $mes], ['anio', $anio] ]);

     if($turnos->count() == 0){
      return 0;
     }else {
      echo $turnos->get()->toJson();
     }

});


Route::get('/TurnosSinLiberar/{id}', function ($id) {

     $turnos = \App\Turnos::where([ ['trabajador_id', $id], ['liberado', '=' , null]]);

     echo  $turnos->get()->toJson();


});




Route::get('/LiberarTurnos/{mes}/{anio}/{id}', function ($mes, $anio, $id) {

    $turnos = \App\Turnos::where([ ['trabajador_id', $id], ['mes',  $mes], ['anio', $anio] ]);

    echo  $turnos->get()->toJson() ;


});



Route::get('/ComisionAfp/{afp}', function ($afp) {

    $afp_comision = \App\afp::where('nombre', $afp);

    echo  $afp_comision->get()->toJson() ;


});



Route::get('/DiasLaboralesCalendarizados/{id}/{mes}/{anio}', function ($id, $mes, $anio) {

    $result = \App\Turnos::where('trabajador_id', $id)->where('mes', $mes)->where('anio', $anio)->get();

    $resultado_array = json_decode($result[0], true);


$contador=1;
$contador_Dias=0;

foreach ($resultado_array as $key => $value) {

$contador++;

  if(strpos($key, 'e') && strpos($value, ':') && $contador> 8){
    $contador_Dias++;

  }

}

echo json_encode($contador_Dias);
});





Route::get('/DiasLaboralesRealizados/{id}/{mes}/{anio}', function ($id, $mes,$anio) {   
  $horasNoTrabajadas = 0;    
  $horasTrabajadas = 0; 
  $tiempoTrabajado = 0;    
  $result =\App\asistencia::where('id_trabajador', $id)->where('mes',$mes)->where('anio', $anio)->get();     
  $contadorEntrada=0;
  $contadorSalida=0;     



foreach ($result as $key => $value) {
       
       if($value["tipo_movimiento"] === "entrada")
           $contadorEntrada++; # No lo usé

           if($value["tipo_movimiento"] === "salida" && \App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'entrada')->count() === 1 ){





      if(\App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'entrada')->where('cuantia_esperada','>', 0)->exists() && \App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'salida')->where('cuantia_esperada','>', 0)->exists()){

                  $horasNoTrabajadasTemp = -1 *  \App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'entrada')->first()['cuantia_diferencia_real_esperada'];  
                  $horasNoTrabajadas += $horasNoTrabajadasTemp;  

                    $horasNoTrabajadasTemp = -1 *  \App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'salida')->first()['cuantia_diferencia_real_esperada'];   
                  $horasNoTrabajadas += $horasNoTrabajadasTemp;  

      }
            /*Horas Trabajadas*/



            if(\App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'entrada')->exists() && \App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'salida')->exists()){

            $cuantiaEntrada_ = (double)(  \App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'entrada')->first()['cuantia_entrada']);


            $cuantiaSalida_ = (double)(\App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'salida')->first()['cuantia_salida']);



           $cuantiaEntrada_time = (double)(  \App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'entrada')->first()['tiempo']);


            $cuantiaSalida_time = (double)(\App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'salida')->first()['tiempo']);

            if(is_numeric(1*$cuantiaEntrada_) && is_numeric(1*$cuantiaSalida_)){
                $horasTrabajadasTemp = $cuantiaSalida_ -$cuantiaEntrada_;   
                $horasTrabajadas += $horasTrabajadasTemp;  
            }


          if(is_numeric(1*$cuantiaEntrada_time) && is_numeric(1*$cuantiaSalida_time)){
                $tiempoTrabajadoTemp = $cuantiaSalida_time - $cuantiaEntrada_time;   
                $tiempoTrabajado += $tiempoTrabajadoTemp;  
            }






            }
           




            /* Fin horas trabajadas*/

               $contadorSalida++;  
           }
          

    }

    $response = array('HorasNoTrabajadas'=> $horasNoTrabajadas, 'diasTrabajados' => $contadorSalida, 'horasTrabajadas' => $horasTrabajadas, 'horasExactas' => ($tiempoTrabajado/3600) );

   // echo $horasNoTrabajadas;
    echo json_encode($response);
});



Route::get('/', function () {
    return view('welcome');
});




Route::get('/DiasLaboralesRealizadosNoche/{id}/{mes}/{anio}', function ($id, $mes,$anio) {   
  $horasNoTrabajadas = 0;    
  $horasTrabajadas = 0; 
  $tiempoTrabajado = 0;    
  $result =\App\asistencia::where('id_trabajador', $id)->where('mes',$mes)->where('anio', $anio)->get();     
  $contadorEntrada=0;
  $contadorSalida=0;     



foreach ($result as $key => $value) {
       
       //echo "key" . $key;
       if($value["tipo_movimiento"] === "entrada")
           $contadorEntrada++; # No lo usé

           if($value["tipo_movimiento"] === "salida" && \App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'entrada')->count() === 1 ){





      if(\App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'entrada')->where('cuantia_esperada','>', 0)->exists() && \App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'salida')->where('cuantia_esperada','>', 0)->exists()){

                  $horasNoTrabajadasTemp = -1 *  \App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'entrada')->first()['cuantia_diferencia_real_esperada'];  
                  $horasNoTrabajadas += $horasNoTrabajadasTemp;  

                    $horasNoTrabajadasTemp = -1 *  \App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'salida')->first()['cuantia_diferencia_real_esperada'];   
                  $horasNoTrabajadas += $horasNoTrabajadasTemp;  

      }
            /*Horas Trabajadas*/



            if(\App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'entrada')->exists() && \App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'salida')->exists()){

            $cuantiaEntrada_ = (double)(  \App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'entrada')->first()['cuantia_entrada']);


            $cuantiaSalida_ = (double)(\App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'salida')->first()['cuantia_salida']);



           $cuantiaEntrada_time = (double)(  \App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'entrada')->first()['tiempo']);


            $cuantiaSalida_time = (double)(\App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'salida')->first()['tiempo']);

            if(is_numeric(1*$cuantiaEntrada_) && is_numeric(1*$cuantiaSalida_)){

                if($cuantiaSalida_ < $cuantiaEntrada_){
                  #Turno de noche
                }else{
                  $horasTrabajadasTemp = $cuantiaSalida_ -$cuantiaEntrada_;   
                  $horasTrabajadas += $horasTrabajadasTemp; 
                }
 
            }


          if(is_numeric(1*$cuantiaEntrada_time) && is_numeric(1*$cuantiaSalida_time)){
                if($cuantiaSalida_time < $cuantiaEntrada_time){
                  #Turno de noche
                  

                  if((\App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $result[$key+2]["dia"])->where('tipo_movimiento', 'salida')->exists())){

                        



           $cuantiaEntrada_time = (double)(  \App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $value["dia"])->where('tipo_movimiento', 'entrada')->first()['tiempo']);


            $cuantiaSalida_time = (double)(\App\asistencia::where('id_trabajador', $id)->where('mes', $mes)->where('anio', $anio)->where('dia', $result[$key+2]["dia"])->where('tipo_movimiento', 'salida')->first()['tiempo']);

           // echo $cuantiaEntrada_time . " Entrada<br>";
           // echo  $value["dia"] . "dia de la entrada...<br>";
           // echo $result[$key+2]["dia"] . "dia de la salida...<br>";
           // echo $cuantiaSalida_time . " Salida<br>";

                          $tiempoTrabajadoTemp = $cuantiaSalida_time - $cuantiaEntrada_time;   
                          $tiempoTrabajado += $tiempoTrabajadoTemp;
                  }else{

                  }






                }else{
                $tiempoTrabajadoTemp = $cuantiaSalida_time - $cuantiaEntrada_time;   
                $tiempoTrabajado += $tiempoTrabajadoTemp;  
                }




            }






            }
           




            /* Fin horas trabajadas*/

               $contadorSalida++;  
           }
          

    }

    $response = array('HorasNoTrabajadas'=> $horasNoTrabajadas, 'diasTrabajados' => $contadorSalida, 'horasTrabajadas' => $horasTrabajadas, 'horasExactas' => ($tiempoTrabajado/3600) );

   // echo $horasNoTrabajadas;
    echo json_encode($response);
});











Route::get('/DiasLaboralesRealizadosProd/{id}/{mes}/{anio}', function ($id, $mes,$anio) {   
  $horasNoTrabajadas = 0;    
  $horasTrabajadas = 0; 
  $tiempoTrabajado = 0;  
  $tiempoTrabajadoExtra = 0;

  $result =\App\asistencia::where('id_trabajador', $id)
  ->where('mes',$mes)
  ->where('anio', $anio)
  ->where('turnoExtra', null)
  ->orderBy('tiempo', 'asc')
  ->get();  

  $resultTurnosExtras =\App\asistencia::where('id_trabajador', $id)
  ->where('mes',$mes)
  ->where('anio', $anio)
  ->where('turnoExtra', 1)
  ->orderBy('tiempo', 'asc')
  ->get();      

  $contadorEntrada=0;
  $contadorSalida=0;     

foreach ($resultTurnosExtras as $key => $value) {
       
       //echo "key" . $key;
       if($value["tipo_movimiento"] === "entrada"){

                if(array_key_exists( ($key+1), $result)){
                       if($result[$key+1]['tipo_movimiento'] === "salida" ){        
                          $tiempoTrabajadoExtra += $result[$key+1]['tiempo'] - $value["tiempo"];
                      }
                }
         
       }
       
}



foreach ($result as $key => $value) {
      echo $value["tipo_movimiento"] . " " . $value["tiempo"] . "<br>";
       //echo "key" . $key;
       if($value["tipo_movimiento"] === "entrada"){
               echo "Es entrada: " . $value["tipo_movimiento"] . " " . $value["tiempo"] . "<br>";
               echo array_key_exists( ($key+1), $result) . "<br>";
               echo isset($result[$key+1]). "<br>";
              if(array_key_exists( ($key+1), $result)){

                 echo "Dentro array key exist : " . $result[$key+1]["tipo_movimiento"] . " " . $result[$key+1]["tiempo"] . "<br>";
                        if($result[$key+1]['tipo_movimiento'] === "salida"){

                         $contadorSalida += 1;
                         $tiempoTrabajado += $result[$key+1]['tiempo'] - $value["tiempo"];
                        }
              }
       }
       
}






    $response = array('horasExactas' => ($tiempoTrabajado/3600), "diasTrabajados" => $contadorSalida, "horasExtras" => $tiempoTrabajadoExtra);

   // echo $horasNoTrabajadas;
    echo json_encode($response);
});











