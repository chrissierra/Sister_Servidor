<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTurnosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turnos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->bigInteger('trabajador_id');
            $table->string('liberado', 100);
            $table->string('mes', 100);
            $table->string('anio', 100);
            $table->string('1e', 100);
            $table->string('1s', 100);
            $table->string('2e', 100);
            $table->string('2s', 100);
            $table->string('3e', 100);
            $table->string('3s', 100);
            $table->string('4e', 100);
            $table->string('4s', 100);
            $table->string('5e', 100);
            $table->string('5s', 100);
            $table->string('6e', 100);
            $table->string('6s', 100);
            $table->string('7e', 100);
            $table->string('7s', 100);
            $table->string('8e', 100);
            $table->string('8s', 100);
            $table->string('9e', 100);
            $table->string('9s', 100);
            $table->string('10e', 100);
            $table->string('10s', 100);
            $table->string('11e', 100);
            $table->string('11s', 100);
            $table->string('12e', 100);
            $table->string('12s', 100);
            $table->string('13e', 100);
            $table->string('13s', 100);
            $table->string('14e', 100);
            $table->string('14s', 100);
            $table->string('15e', 100);
            $table->string('15s', 100);
            $table->string('16e', 100);
            $table->string('16s', 100);
            $table->string('17e', 100);
            $table->string('17s', 100);
            $table->string('18e', 100);
            $table->string('18s', 100);
            $table->string('19e', 100);
            $table->string('19s', 100);
            $table->string('20e', 100);
            $table->string('20s', 100);
            $table->string('21e', 100);
            $table->string('21s', 100);
            $table->string('22e', 100);
            $table->string('22s', 100);
            $table->string('23e', 100);
            $table->string('23s', 100);
            $table->string('24e', 100);
            $table->string('24s', 100);
            $table->string('25e', 100);
            $table->string('25s', 100);
            $table->string('26e', 100);
            $table->string('26s', 100);
            $table->string('27e', 100);
            $table->string('27s', 100);
            $table->string('28e', 100);
            $table->string('28s', 100);
            $table->string('29e', 100);
            $table->string('29s', 100);
            $table->string('30e', 100);
            $table->string('30s', 100);
            $table->string('31e', 100);
            $table->string('31s', 100);
            $table->string('32e', 100);
            $table->string('32s', 100);
            $table->string('33e', 100);
            $table->string('33s', 100);
            $table->string('34e', 100);
            $table->string('34s', 100);
            $table->string('35e', 100);
            $table->string('35s', 100);
            $table->string('36e', 100);
            $table->string('36s', 100);
            $table->string('37e', 100);
            $table->string('37s', 100);
            $table->string('38e', 100);
            $table->string('38s', 100);
            $table->string('39e', 100);
            $table->string('39s', 100);
            $table->string('40e', 100);
            $table->string('40s', 100);
            $table->string('41e', 100);
            $table->string('41s', 100);
            $table->string('42e', 100);
            $table->string('42s', 100);
            $table->string('43e', 100);
            $table->string('43s', 100);
            $table->string('44e', 100);
            $table->string('44s', 100);
            $table->string('45e', 100);
            $table->string('45s', 100);
            $table->string('46e', 100);
            $table->string('46s', 100);
            $table->string('47e', 100);
            $table->string('47s', 100);
            $table->string('48e', 100);
            $table->string('48s', 100);
            $table->string('49e', 100);
            $table->string('49s', 100);
            $table->string('50e', 100);
            $table->string('50s', 100);
            $table->string('51e', 100);
            $table->string('51s', 100);
            $table->string('52e', 100);
            $table->string('52s', 100);
            $table->string('53e', 100);
            $table->string('53s', 100);
            $table->string('54e', 100);
            $table->string('54s', 100);
            $table->string('55e', 100);
            $table->string('55s', 100);
            $table->string('56e', 100);
            $table->string('56s', 100);
            $table->string('57e', 100);
            $table->string('57s', 100);
            $table->string('58e', 100);
            $table->string('58s', 100);
            $table->string('59e', 100);
            $table->string('59s', 100);
            $table->string('60e', 100);
            $table->string('60s', 100);
            $table->string('61e', 100);
            $table->string('61s', 100);
            $table->string('62e', 100);
            $table->string('62s', 100);


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('turnos');
    }
}

