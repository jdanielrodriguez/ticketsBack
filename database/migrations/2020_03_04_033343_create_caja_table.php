<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCajaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caja', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo')->nullable()->default(null);
            $table->string('descripcion')->nullable()->default(null);
            $table->string('fecha_inicio')->nullable()->default(null);
            $table->string('fecha_fin')->nullable()->default(null);
            $table->double('total',5,2)->nullable()->default(null);
            $table->integer('cerrado')->nullable()->default(null);
            $table->integer('type')->nullable()->default(1);
            $table->integer('state')->nullable()->default(1);

            $table->integer('usuario')->nullable()->default(null)->unsigned();
            $table->foreign('usuario')->references('id')->on('users')->onDelete('cascade');

            $table->integer('creador')->nullable()->default(null)->unsigned();
            $table->foreign('creador')->references('id')->on('users')->onDelete('cascade');

            $table->integer('evento_funcion')->nullable()->default(null)->unsigned();
            $table->foreign('evento_funcion')->references('id')->on('eventos_funciones')->onDelete('cascade');

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
        Schema::dropIfExists('caja');
    }
}
