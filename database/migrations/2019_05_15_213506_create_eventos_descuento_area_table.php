<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosDescuentoAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos_descuento_area', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo')->nullable()->default(null);
            $table->string('descripcion')->nullable()->default(null);
            $table->double('cantidad')->nullable()->default(null);
            $table->integer('type')->nullable()->default(1);
            $table->integer('state')->nullable()->default(1);

            $table->integer('evento_descuento')->nullable()->default(null)->unsigned();
            $table->foreign('evento_descuento')->references('id')->on('eventos_descuento')->onDelete('cascade');

            $table->integer('evento_funcion_area')->nullable()->default(null)->unsigned();
            $table->foreign('evento_funcion_area')->references('id')->on('eventos_funciones_area')->onDelete('cascade');

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
        Schema::dropIfExists('eventos_descuento_area');
    }
}
