<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosFuncionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos_funciones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo')->nullable()->default(null);
            $table->string('imagen')->nullable()->default(null);
            $table->string('descripcion')->nullable()->default(null);
            $table->string('direccion')->nullable()->default(null);
            $table->time('hora_inicio')->nullable()->default(null);
            $table->time('hora_fin')->nullable()->default(null);
            $table->date('fecha_inicio')->nullable()->default(null);
            $table->date('fecha_fin')->nullable()->default(null);
            $table->timestamp('inicio')->nullable()->default(null);
            $table->timestamp('fin')->nullable()->default(null);

            $table->double('latitud',15,8)->nullable()->default(null);
            $table->double('longitud',15,8)->nullable()->default(null);
            $table->integer('type')->nullable()->default(1);
            $table->integer('state')->nullable()->default(1);

            $table->integer('evento')->nullable()->default(null)->unsigned();
            $table->foreign('evento')->references('id')->on('eventos')->onDelete('cascade');

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
        Schema::dropIfExists('eventos_funciones');
    }
}
