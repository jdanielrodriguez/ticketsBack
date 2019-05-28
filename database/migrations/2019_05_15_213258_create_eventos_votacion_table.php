<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosVotacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos_votacion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo')->nullable()->default(null);
            $table->double('valor')->nullable()->default(null);
            $table->timestamp('fecha')->useCurrent();
            $table->string('comentario')->nullable()->default(null);
            $table->string('descripcion')->nullable()->default(null);
            $table->integer('type')->nullable()->default(1);
            $table->integer('state')->nullable()->default(1);

            $table->integer('usuario')->nullable()->default(null)->unsigned();
            $table->foreign('usuario')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('eventos_votacion');
    }
}
