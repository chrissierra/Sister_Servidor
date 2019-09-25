<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTurnosHorariosExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('res_extra', function (Blueprint $table) {
            
            $table->increments('id');

            $table->unsignedInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('clientes_rrhh');

            $table->unsignedInteger('sucursal_id')->index();            
            $table->foreign('sucursal_id')->references('id')->on('sucursales');

            $table->unsignedInteger('trabajador_id')->index();
            $table->foreign('trabajador_id')->references('id')->on('ingreso_empleados');

            $table->unsignedInteger('supervisor_id')->index();
            $table->foreign('supervisor_id')->references('id')->on('ingreso_empleados');



            $table->integer('id_movimiento_unico')->unique();
            $table->integer('monto');
            $table->integer('dia');
            $table->integer('mes');
            $table->integer('anio');
            $table->string('tipo', 100)->comment('Tipo de hito. Hora Extra o Turno extra');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('turnos_horarios_extras');
    }
}
