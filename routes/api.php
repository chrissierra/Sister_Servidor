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

Route::post('update_sucursales', 'SucursalController@update_sucursales');

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

Route::post('MarcarMovimientoWebNoches', 'marcajeTrabajadoresSinTurnoEstablecido@MarcarMovimientoWebNoches'); // < --- Si no hay turno hecho 

Route::post('MarcarMovimientoSinTurnoEstablecidoWeb', 'marcajeTrabajadoresSinTurnoEstablecido@MarcarMovimientoWeb'); // < --- Si no hay turno hecho 

Route::post('MarcarMovimientoTurnoExtra', 'marcajeTrabajadoresSinTurnoEstablecido@MarcarMovimientoTurnoExtra'); // < --- TURNOS EXTRAS 

Route::post('libroremuneraciondiario', 'libroremuneraciones@diario');

Route::post('diarioPorTrabajador', 'libroremuneraciones@diarioPorTrabajador');

Route::post('mensualPorTrabajador', 'libroremuneraciones@mensualPorTrabajador');

Route::post('mensualPorSucursal', 'libroremuneraciones@mensualPorSucursal');

Route::post('diarioPorSucursal', 'libroremuneraciones@diarioPorSucursal');

Route::post('libroremuneracionmensual', 'libroremuneraciones@mensual');

Route::post('diarioUltimos', 'libroremuneraciones@diarioUltimos');

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

Route::post('logueo', 'mandanteController@logueo');

Route::post('actualizarDatosClientes', 'clientesrrhh@actualizarDatosClientes');

Route::post('GetDatosClientes', 'clientesrrhh@GetDatosClientes');

Route::post('desvincular', 'desvinculadosController@desvincular');

Route::post('GuardarTurnoNoche', 'TurnoNocheController@GuardarTurnoNoche');

Route::post('GetTurnoNoche', 'TurnoNocheController@GetTurnoNoche');

Route::post('UpdateTurnoNoche', 'TurnoNocheController@UpdateTurnoNoche');

Route::post('GetViaticos', 'viaticosController@GetViaticos');

Route::post('InsertViaticos', 'viaticosController@InsertViaticos');

Route::post('GetViaticosPorTrabajador', 'viaticosController@GetViaticosPorTrabajador');

Route::post('GetViaticosPorEmpleador', 'viaticosController@GetViaticosPorEmpleador');

Route::post('actualmenteTrabajando', 'libroremuneraciones@actualmenteTrabajando');

Route::post('actualmenteTrabajandoPorSucursal', 'libroremuneraciones@actualmenteTrabajandoPorSucursal');

Route::post('getmovimientounitario', 'libroremuneraciones@getmovimientounitario');

Route::post('MarcarMovimiento_offline', 'asistencia_offline_controller@MarcarMovimiento');

Route::post('getAsistenciaOfflineDiario', 'asistencia_offline_controller@getAsistenciaOfflineDiario');

Route::post('getAsistenciaOfflineMensual', 'asistencia_offline_controller@getAsistenciaOfflineMensual');

// departamento:

Route::post('ingresardepartamento', 'departamento@ingresardepartamento');

Route::post('actualizardepartamento', 'departamento@actualizardepartamento');

Route::post('getdepartamento', 'departamento@getdepartamento');

Route::post('deletedepartamento', 'departamento@deletedepartamento');

// centro de costo:

Route::post('ingresar_centrocosto', 'centro_de_costo@ingresar_centro_de_costo');

Route::post('actualizar_centrocosto', 'centro_de_costo@actualizar_centro_de_costo');

Route::post('get_centrocosto', 'centro_de_costo@get_centro_de_costo');

Route::post('delete_centrocosto', 'centro_de_costo@delete_centro_de_costo');

// jefaturas:

Route::post('ingresarjefaturas', 'jefaturas@ingresarjefatura');

Route::post('actualizarjefaturas', 'jefaturas@actualizarjefatura');

Route::post('getjefaturas', 'jefaturas@getjefaturas');

Route::post('deletejefaturas', 'jefaturas@deletejefaturas');

// horario_por_sucursal:

Route::post('ingresar_horario_por_sucursal', 'horario_por_sucursal@ingresar_horario_por_sucursal');

Route::post('actualizar_horario_por_sucursal', 'horario_por_sucursal@actualizar_horario_por_sucursal');

Route::post('get_horario_por_sucursal', 'horario_por_sucursal@get_horario_por_sucursal');

Route::post('delete_horario_por_sucursal', 'horario_por_sucursal@delete_horario_por_sucursal');

// Subir Archivos CSV 

Route::post('importacion_trabajadores', 'IngresoEmpleados@Importacion_Trabajadores');

Route::post('getContrasteFotograficoValidacion', 'ContrasteFotograficoValidacion@getContrasteFotograficoValidacion');


Route::post('UpdateContrasteFotograficoValidacion', 'ContrasteFotograficoValidacion@UpdateContrasteFotograficoValidacion');