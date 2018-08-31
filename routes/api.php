<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

// Testeos:
Route::resource('kk', 'crudController');

Route::resource('jj', 'crudController2');

// Desarrollo APP SISTER : Servidor.

Route::resource('login', 'LoginController');

Route::resource('planilla', 'PlanillaController');

Route::resource('TurnosVariables', 'turnosVariables');

Route::post('ActualizarTurnosVariables', function(Request $request){

    $post = $request->json()->all();

    foreach ($post as $key => $value) {
        # code...
                if(strcmp( $key, 'id' ) !== 0 && strcmp( $key, 'check' ) !== 0 && strcmp( $key, 'mes' ) !== 0){

                     \App\Turnos::where( 'trabajador_id', $post['id'] )->where(  'mes', $post['mes'] )->update([$key => $value]);
                }
    }

    \App\Turnos::where( 'trabajador_id', $post['id'] )->where(  'mes', $post['mes'] )->update(['liberado' => NULL]);

});



Route::post('LiberarDefinitivoTurnos', function(Request $request){

    $post = $request->json()->all();

    foreach ($post as $key => $value) {
        # code...
                if(strcmp( $key, 'id' ) !== 0 && strcmp( $key, 'check' ) !== 0 && strcmp( $key, 'mes' ) !== 0){

                     \App\Turnos::where( 'trabajador_id', $post['id'] )->where(  'mes', $post['mes'] )->update([$key => $value]);
                }
    }

    \App\Turnos::where( 'trabajador_id', $post['id'] )->where(  'mes', $post['mes'] )->update(['liberado' => true]);

});

Route::post('GuardarSucursal', 'SucursalController@ingreso_sucursal');

Route::post('SituacionMarcaje', 'MarcajeController@SituacionMarcajeActual');