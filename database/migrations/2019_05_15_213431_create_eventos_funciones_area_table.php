<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosFuncionesAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos_funciones_area', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo')->nullable()->default(null);
            $table->string('descripcion')->nullable()->default(null);
            $table->double('precio')->nullable()->default(null);
            $table->double('total')->nullable()->default(null);
            $table->double('vendidos')->nullable()->default(null);
            $table->integer('type')->nullable()->default(1);
            $table->integer('state')->nullable()->default(1);

            $table->integer('tipo')->nullable()->default(null)->unsigned();
            $table->foreign('tipo')->references('id')->on('tipo_eventos')->onDelete('cascade');

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
        Schema::dropIfExists('eventos_funciones_area');
    }
}
