<?php


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

Route::get('/perfil_trabajador/{id}', function ($id) {

     $planilla = \App\ingreso_empleados::where('id', $id)->get();

     echo $planilla->toJson();

});



Route::get('/estatusturnos/{id}/{mes}/{anio}', function ($id, $mes, $anio) {

     $turnos = \App\turnos::where([ ['trabajador_id', $id], ['mes', $mes], ['anio', $anio] ]);

     if($turnos->count() == 0){
      return 0;
     }else {
      echo $planilla->get()->toJson();
     }

});


Route::get('/TurnosSinLiberar/{id}', function ($id) {

     $turnos = \App\turnos::where([ ['trabajador_id', $id], ['liberado', '=' , null]]);

     echo  $turnos->get()->toJson();


});




Route::get('/LiberarTurnos/{mes}/{anio}/{id}', function ($mes, $anio, $id) {

    $turnos = \App\turnos::where([ ['trabajador_id', $id], ['mes',  $mes], ['anio', $anio] ]);

    echo  $turnos->get()->toJson() ;


});






/*
TESTEOS
//echo json_encode($planilla->toarray(),JSON_PARTIAL_OUTPUT_ON_ERROR);
Route::get('/', function () {
    return view('welcome');
});
Route::get('/leer', 'Ejemplo1@test');
Route::get('/peo', function(){
$hola= new \App\clientes_rrhh;
 $hola->nombre_empresa = "Phillips";
  $hola->nombre_rep = "Mr. Phillips";
  $hola->email = "chris@chris.com";
  $hola->numero = "82848955";
     $hola->direccion="";
   $hola->website="";
   $hola->password="";
   $hola->textarea="";
   $hola->rut_empresa="";
   $hola->rut_rep="";
   $hola->giro="";
   $hola->numero_empleados="";
   $hola->nacimiento="";
   $hola->estatus="";

  $hola->save();
});



Route::get('/actualizar/{correo}', function($correo){

$hola=  \App\clientes_rrhh::find(7);

  $hola->email = $correo;
  $hola->numero = "94746162";


  $hola->save();

  echo " Se ha actualizado al correo " . $correo;

});
*/
//Route::post('', 'crudController@store');
//Route::post('kk', 'crudController@store');

//Route::post('hola', 'LoginController@store'); // No es necesario ponerlo, ya está lista la ruta.
