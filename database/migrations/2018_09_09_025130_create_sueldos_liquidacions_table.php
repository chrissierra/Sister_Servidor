<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSueldosLiquidacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sueldos_liquidacions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('haberesImponibles');
            $table->integer('haberesNoImponibles');
            $table->integer('trabajador_id');
            $table->integer('dias_calendarizados');
            $table->string('empresa', 100);
            $table->integer('dias_trabajados');
            $table->string('sueldo_escrito', 100);
            $table->integer('descuentos');
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
        Schema::dropIfExists('sueldos_liquidacions');
    }
}

