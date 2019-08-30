<?php
use Illuminate\Support\Facades\Storage;
use App\Clases\DiasTrabajados;

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


Route::get('/test/{id}/{mes}/{anio}', function ($id, $mes, $anio) {

    $peo = new DiasTrabajados();

    echo $peo->DiasTrabajadosPorTrabajador($id, $mes, $anio);

});

Route::get('/presta', function () {
    ob_end_clean();
    Fpdf::AddPage();
    Fpdf::SetFont('Courier', 'B', 18);
    Fpdf::Cell(50, 25, 'Hello World!');
    
    return response(Fpdf::Output("I"), 200)->header('Content-Type', 'text/pdf');

  // jjj
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

  // Ver días DT para cada uno. $diasTrabajadosBaseDe30->procesar($id, $mes,$anio)
  // procesar : if de si es fijo, variable o de noche. 
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

                if(isset($resultTurnosExtras[$key+1])){
                       if($resultTurnosExtras[$key+1]['tipo_movimiento'] === "salida" ){        
                          $tiempoTrabajadoExtra += $resultTurnosExtras[$key+1]['tiempo'] - $value["tiempo"];
                      }
                }
         
       }
       
}



foreach ($result as $key => $value) {
          

       if($value["tipo_movimiento"] === "entrada"){
             
              if(isset($result[$key+1])){

                        if($result[$key+1]['tipo_movimiento'] === "salida"){

                                  if($value["cuantia_diferencia_real_esperada"]<0 && $result[$key+1]["cuantia_diferencia_real_esperada"] < 0){
                                    $horasNoTrabajadas += (-1* $value["cuantia_diferencia_real_esperada"]) + (-1* $result[$key+1]["cuantia_diferencia_real_esperada"]);
                                  }elseif ($value["cuantia_diferencia_real_esperada"]>0 && $result[$key+1]["cuantia_diferencia_real_esperada"] > 0) {
                                    # code...
                                     $horasNoTrabajadas += ( $value["cuantia_diferencia_real_esperada"]) + ( $result[$key+1]["cuantia_diferencia_real_esperada"]);
                                  }elseif ($value["cuantia_diferencia_real_esperada"]<0 && $result[$key+1]["cuantia_diferencia_real_esperada"] > 0) {
                                    # code...
                                     $horasNoTrabajadas += ( $value["cuantia_diferencia_real_esperada"]) + ( $result[$key+1]["cuantia_diferencia_real_esperada"]);
                                  }elseif ($value["cuantia_diferencia_real_esperada"]>0 && $result[$key+1]["cuantia_diferencia_real_esperada"] < 0) {
                                    # code...
                                     $horasNoTrabajadas += ( $result[$key+1]["cuantia_diferencia_real_esperada"])+( $value["cuantia_diferencia_real_esperada"]) ;
                                  }
                         $contadorSalida += 1;
                         $tiempoTrabajado += $result[$key+1]['tiempo'] - $value["tiempo"];
                        }
              }
       }
       
}






    $response = array('horasExactas' => ($tiempoTrabajado/3600), "diasTrabajados" => $contadorSalida, "horasExtras" => $tiempoTrabajadoExtra/3600, 'horasNoTrabajadas' => $horasNoTrabajadas);

   // echo $horasNoTrabajadas;
    echo json_encode($response);
});




Route::get('/TurnosSinTerminar/{usuario_cliente}/{mes}/{anio}', function ($usuario_cliente, $mes,$anio) {   
 

    $planilla = \App\ingreso_empleados::where('nombre_empresa_usuario_plataforma', $usuario_cliente)->get();
    $TurnosSinTerminar= array();
    foreach ($planilla as $key => $value) {
      # code...
       $result =\App\asistencia::where('id_trabajador', $value['id'])
                ->where('mes',$mes)
                ->where('anio', $anio)
                ->orderBy('tiempo', 'asc')
                ->get();  

                
                //var_dump($result);
        foreach ($result as $key => $value) {
                        

                     if($value["tipo_movimiento"] === "entrada"){
                           
                            if(isset($result[$key+1])){

                                      if($result[$key+1]['tipo_movimiento'] !== "salida"){

                                        array_push($TurnosSinTerminar, $result[$key]);

                                       }
                            }
                     }
                     
        }
    }
    

      $response = array('turnosSinTerminar' => $TurnosSinTerminar);

     // echo $horasNoTrabajadas;
      echo json_encode($response);



});




















Route::get('/TurnosSinTerminarPorTrabajador/{id_trabajador}/{mes}/{anio}', function ($id_trabajador, $mes,$anio) {   
 
   $result =\App\asistencia::where('id_trabajador', $id_trabajador)
  ->where('mes',$mes)
  ->where('anio', $anio)
  ->orderBy('tiempo', 'asc')
  ->get();  

  $TurnosSinTerminar= array();
  //var_dump($result);
foreach ($result as $key => $value) {
          

       if($value["tipo_movimiento"] === "entrada"){
             
              if(isset($result[$key+1])){

                        if($result[$key+1]['tipo_movimiento'] !== "salida"){

                          array_push($TurnosSinTerminar, $result[$key]);

                        }
              }
       }
       
}
    $response = array('turnosSinTerminar' => $TurnosSinTerminar);

   // echo $horasNoTrabajadas;
    echo json_encode($response);
});




Route::get('/TurnosSinTerminarPorSucursal/{usuario_cliente}/{mes}/{anio}/{sucursal}', function ($usuario_cliente, $mes,$anio, $sucursal) {   
 
    $planilla = \App\ingreso_empleados::where('nombre_empresa_usuario_plataforma', $usuario_cliente)->get();
    $TurnosSinTerminar= array();
    foreach ($planilla as $key => $value) {
      # code...
       $result =\App\asistencia::where('id_trabajador', $value['id'])
                ->where('sucursal', $sucursal)
                ->where('mes',$mes)
                ->where('anio', $anio)
                ->orderBy('tiempo', 'asc')
                ->get();  

                
                //var_dump($result);
        foreach ($result as $key => $value) {
                        

                     if($value["tipo_movimiento"] === "entrada"){
                           
                            if(isset($result[$key+1])){

                                      if($result[$key+1]['tipo_movimiento'] !== "salida"){

                                        array_push($TurnosSinTerminar, $result[$key]);

                                       }
                            }
                     }
                     
        }
    }
    

      $response = array('turnosSinTerminar' => $TurnosSinTerminar);

     // echo $horasNoTrabajadas;
      echo json_encode($response);




});





/*
// *****************************************


Para Gráficas:


//******************************************

*/



Route::get('/HorasPorSucursalMes/{id}/{mes}/{anio}/{sucursal}', function ($id, $mes,$anio, $sucursal) {   
     


  $planilla = \App\ingreso_empleados::where('nombre_empresa_usuario_plataforma', $id)->get();
  $totalNormal = 0;
  $totalTurnos = 0;


  foreach ($planilla as $key => $value) {
                  # code...
                $horasNoTrabajadas = 0;    
                $horasTrabajadas = 0; 
                $tiempoTrabajado = 0;  
                $tiempoTrabajadoExtra = 0;
                $contadorEntrada=0;
                $contadorSalida=0;     

                $result =\App\asistencia::where('id_trabajador', $value['id'])
                ->where('mes',$mes)
                ->where('anio', $anio)
                ->where('turnoExtra', null)
                ->where('sucursal', $sucursal)
                ->orderBy('tiempo', 'asc')
                ->get();  



                $resultTurnosExtras =\App\asistencia::where('id_trabajador', $value['id'])
                ->where('mes',$mes)
                ->where('anio', $anio)
                ->where('turnoExtra', 1)
                ->where('sucursal', $sucursal)
                ->orderBy('tiempo', 'asc')
                ->get();   


              foreach ($resultTurnosExtras as $key => $value) {
                     
                     //echo "key" . $key;
                     if($value["tipo_movimiento"] === "entrada"){

                              if(isset($resultTurnosExtras[$key+1])){
                                     if($resultTurnosExtras[$key+1]['tipo_movimiento'] === "salida" ){        
                                        $tiempoTrabajadoExtra += $resultTurnosExtras[$key+1]['tiempo'] - $value["tiempo"];
                                    }
                              }
                       
                     }
                     
              }



              foreach ($result as $key => $value) {
                        

                     if($value["tipo_movimiento"] === "entrada"){
                           
                            if(isset($result[$key+1])){

                                      if($result[$key+1]['tipo_movimiento'] === "salida"){

                                                if($value["cuantia_diferencia_real_esperada"]<0 && $result[$key+1]["cuantia_diferencia_real_esperada"] < 0){
                                                  $horasNoTrabajadas += (-1* $value["cuantia_diferencia_real_esperada"]) + (-1* $result[$key+1]["cuantia_diferencia_real_esperada"]);
                                                }elseif ($value["cuantia_diferencia_real_esperada"]>0 && $result[$key+1]["cuantia_diferencia_real_esperada"] > 0) {
                                                  # code...
                                                   $horasNoTrabajadas += ( $value["cuantia_diferencia_real_esperada"]) + ( $result[$key+1]["cuantia_diferencia_real_esperada"]);
                                                }elseif ($value["cuantia_diferencia_real_esperada"]<0 && $result[$key+1]["cuantia_diferencia_real_esperada"] > 0) {
                                                  # code...
                                                   $horasNoTrabajadas += ( $value["cuantia_diferencia_real_esperada"]) + ( $result[$key+1]["cuantia_diferencia_real_esperada"]);
                                                }elseif ($value["cuantia_diferencia_real_esperada"]>0 && $result[$key+1]["cuantia_diferencia_real_esperada"] < 0) {
                                                  # code...
                                                   $horasNoTrabajadas += ( $result[$key+1]["cuantia_diferencia_real_esperada"])+( $value["cuantia_diferencia_real_esperada"]) ;
                                                }
                                       $contadorSalida += 1;
                                       $tiempoTrabajado += $result[$key+1]['tiempo'] - $value["tiempo"];
                                      }
                            }
                     }
                     
              }


              $totalTurnos += $tiempoTrabajadoExtra;              
              $totalNormal += $tiempoTrabajado;


  } // FIn foreach planilla








    $response = array('horasExactas' => ($totalNormal/3600), 'horasExtras' => ($totalTurnos/3600));

   // echo $horasNoTrabajadas;
    echo json_encode($response);
});








Route::get('/HorasPorSucursalDia/{id}/{mes}/{anio}/{dia}/{sucursal}', function ($id, $mes,$anio,$dia, $sucursal) {   


      $planilla = \App\ingreso_empleados::where('nombre_empresa_usuario_plataforma', $id)->get();
      $totalNormal = 0;
      $totalTurnos = 0;


  foreach ($planilla as $key => $value) {
                  # code...
                $horasNoTrabajadas = 0;    
                $horasTrabajadas = 0; 
                $tiempoTrabajado = 0;  
                $tiempoTrabajadoExtra = 0;
                $contadorEntrada=0;
                $contadorSalida=0;     

                $result =\App\asistencia::where('id_trabajador', $value['id'])
                ->where('mes',$mes)
                ->where('anio', $anio)
                ->where('dia', $dia)
                ->where('turnoExtra', null)
                ->where('sucursal', $sucursal)
                ->orderBy('tiempo', 'asc')
                ->get();  



                $resultTurnosExtras =\App\asistencia::where('id_trabajador', $value['id'])
                ->where('mes',$mes)
                ->where('anio', $anio)
                ->where('dia', $dia)
                ->where('turnoExtra', 1)
                ->where('sucursal', $sucursal)
                ->orderBy('tiempo', 'asc')
                ->get();   


              foreach ($resultTurnosExtras as $key => $value) {
                     
                     //echo "key" . $key;
                     if($value["tipo_movimiento"] === "entrada"){

                              if(isset($resultTurnosExtras[$key+1])){
                                     if($resultTurnosExtras[$key+1]['tipo_movimiento'] === "salida" ){        
                                        $tiempoTrabajadoExtra += $resultTurnosExtras[$key+1]['tiempo'] - $value["tiempo"];
                                    }
                              }
                       
                     }
                     
              }



              foreach ($result as $key => $value) {
                        

                     if($value["tipo_movimiento"] === "entrada"){
                           
                            if(isset($result[$key+1])){

                                      if($result[$key+1]['tipo_movimiento'] === "salida"){

                                                if($value["cuantia_diferencia_real_esperada"]<0 && $result[$key+1]["cuantia_diferencia_real_esperada"] < 0){
                                                  $horasNoTrabajadas += (-1* $value["cuantia_diferencia_real_esperada"]) + (-1* $result[$key+1]["cuantia_diferencia_real_esperada"]);
                                                }elseif ($value["cuantia_diferencia_real_esperada"]>0 && $result[$key+1]["cuantia_diferencia_real_esperada"] > 0) {
                                                  # code...
                                                   $horasNoTrabajadas += ( $value["cuantia_diferencia_real_esperada"]) + ( $result[$key+1]["cuantia_diferencia_real_esperada"]);
                                                }elseif ($value["cuantia_diferencia_real_esperada"]<0 && $result[$key+1]["cuantia_diferencia_real_esperada"] > 0) {
                                                  # code...
                                                   $horasNoTrabajadas += ( $value["cuantia_diferencia_real_esperada"]) + ( $result[$key+1]["cuantia_diferencia_real_esperada"]);
                                                }elseif ($value["cuantia_diferencia_real_esperada"]>0 && $result[$key+1]["cuantia_diferencia_real_esperada"] < 0) {
                                                  # code...
                                                   $horasNoTrabajadas += ( $result[$key+1]["cuantia_diferencia_real_esperada"])+( $value["cuantia_diferencia_real_esperada"]) ;
                                                }
                                       $contadorSalida += 1;
                                       $tiempoTrabajado += $result[$key+1]['tiempo'] - $value["tiempo"];
                                      }
                            }
                     }
                     
              }


              $totalTurnos += $tiempoTrabajadoExtra;              
              $totalNormal += $tiempoTrabajado;


  } // FIn foreach planilla








    $response = array('horasExactas' => ($totalNormal/3600), 'horasExtras' => ($totalTurnos/3600));

   // echo $horasNoTrabajadas;
    echo json_encode($response);
});



/*
  TODO ESTO ES PARA VER LA PLANILLA TIPO AGR:

*/


  Route::get('/LibroTipoPlanillaAsistencia/{id}/{mes}/{anio}', function ($id, $mes,$anio) {   

    $numero = cal_days_in_month(CAL_GREGORIAN,$mes, $anio); // 31

  $nombre = '';
  $apellido = '';
  $tiempoTrabajado = 0;  
  $tiempoTrabajadoExtra = 0;
  $contadorSalida=0; 
  $respuestaExtras = Array();
  $respuestaNormal = Array();

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

  $rut = '12341234';
 // $apellido = Array($result[0]['apellido']);

foreach ($resultTurnosExtras as $key => $value) {
       
       //echo "key" . $key;
       if($value["tipo_movimiento"] === "entrada"){

                if(isset($resultTurnosExtras[$key+1])){
                       if($resultTurnosExtras[$key+1]['tipo_movimiento'] === "salida" ){        
                          $tiempoTrabajadoExtra += $resultTurnosExtras[$key+1]['tiempo'] - $value["tiempo"];
                          array_push($respuestaExtras, 2);
                      }else{
                          array_push($respuestaExtras, 1);
                      }
                }
         
       }
       
}




          
$marcador=0;
    for ($i=1; $i <= $numero; $i++) { 
            foreach ($result as $key => $value) {
              $nombre = $value['nombre'];
             
              if($value['dia'] === $i){
                
                                if($value["tipo_movimiento"] === "entrada"){
                               // var_dump($value);
                               // echo '<br><br>';
                                          if(isset($result[$key+1])){

                                                    if($result[$key+1]['tipo_movimiento'] === "salida"){

                                                     $contadorSalida += 1;
                                                     $tiempoTrabajado += $result[$key+1]['tiempo'] - $value["tiempo"];
                                                     
                                                    $marcador=1;
                                                     array_push($respuestaNormal, 2);
                                                    }else{
                                                      $marcador=1;
                                                        array_push($respuestaNormal, 1);
                                                    }
                                          }
                                  }


              }else{
                
              }



    
    }

    if($marcador === 0){
      array_push($respuestaNormal, '');

    }else{
      $marcador = 0;
    } 

       
}

     $planilla = \App\ingreso_empleados::where('id', $id)->get();  

    $response = array("nombre"=> $nombre ,"respuesta" => $respuestaNormal, "rut" => $planilla[0]['rut'], "apellido" => $planilla[0]['apellido'] );
    echo json_encode($response);
   // echo $horasNoTrabajadas;
    //echo json_encode($response);
});
