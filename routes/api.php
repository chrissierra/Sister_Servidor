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

Route::post('get_sucursales', 'SucursalController@get_sucursales');

Route::post('SituacionMarcaje', 'MarcajeController@SituacionMarcajeActual');

Route::post('MarcarMovimiento', 'MarcajeController@MarcarMovimiento'); // < --- Si hay turno hecho 

Route::post('LiberarSueldo', 'liberarSueldos@InsertSueldo');

Route::post('ConfirmarEstadoSueldo', 'liberarSueldos@getSueldoLiberado');

Route::post('SueldosLiberados', 'liberarSueldos@getSueldosLiberados');

Route::post('SueldosLiberadosPorFecha', 'liberarSueldos@getSueldosLiberadosPorFecha');

Route::post('InsertTurnoFijo', 'turnosFijos@store');

Route::post('updateTurnoFijo', 'turnosFijos@update');

Route::post('getTurnos', 'turnosFijos@getTurnos');

Route::post('UpdateTurnoFijo', 'turnosFijos@updateTurnos');

Route::post('GetAsistenciaMesAnterior', 'turnosFijos@DiasTrabajados');

Route::post('Enrolamiento', 'IngresoEmpleados@Enrolamiento');

Route::post('Actualizacion_registro_Trabajadores', 'IngresoEmpleados@Actualizacion_registro_Trabajadores');

Route::post('guardarImagenesProcesoBiometricoEnMarcaje', 'MarcajeDesdeApp@guardarImagenesProcesoBiometricoEnMarcaje');

Route::post('VerificarUltimoMovimiento', 'marcajeTrabajadoresSinTurnoEstablecido@VerificarUltimoMovimiento');

Route::post('VerificarUltimoMovimientoTurnoExtra', 'marcajeTrabajadoresSinTurnoEstablecido@VerificarUltimoMovimientoTurnoExtra');

Route::post('MarcarMovimientoSinTurnoEstablecido', 'marcajeTrabajadoresSinTurnoEstablecido@MarcarMovimiento'); // < --- Si no hay turno hecho 

Route::post('MarcarMovimientoSinTurnoEstablecidoWeb', 'marcajeTrabajadoresSinTurnoEstablecido@MarcarMovimientoWeb'); // < --- Si no hay turno hecho 

Route::post('MarcarMovimientoTurnoExtra', 'marcajeTrabajadoresSinTurnoEstablecido@MarcarMovimientoTurnoExtra'); // < --- TURNOS EXTRAS 

Route::post('libroremuneraciondiario', 'libroremuneraciones@diario');

Route::post('libroremuneracionmensual', 'libroremuneraciones@mensual');

Route::post('loginTrabajador', 'loginTrabajador@loginTrabajadorDashboard');

Route::post('loginSucursal', 'loginTrabajador@loginSucursal');

Route::post('ingresarClaves', 'contraseniasController@ingresarClaves');

Route::post('updateClaves', 'contraseniasController@UpdateClaves');

Route::post('getClaves', 'contraseniasController@getClaves');

Route::post('ingresarMandante', 'mandanteController@ingresarMandante');

Route::post('actualizarMandante', 'mandanteController@actualizarMandante');

Route::post('getMandante', 'mandanteController@getMandante');

Route::post('deleteMandante', 'mandanteController@deleteMandante');

Route::post('ingresarCargo', 'cargosController@ingresarCargo');

Route::post('actualizarCargo', 'cargosController@actualizarCargo');

Route::post('getCargos', 'cargosController@getCargos');

Route::post('deleteCargos', 'cargosController@deleteCargos');

Route::post('ingresarHitos', 'hitosController@ingresarHitos');

Route::post('VisualizarHitos', 'hitosController@VisualizarHitos');

Route::post('getMandantePorRut', 'mandanteController@getMandantePorRut');