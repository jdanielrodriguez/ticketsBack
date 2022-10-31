<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos_venta', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo')->nullable()->default(null);
            $table->string('lugar')->nullable()->default(null);
            $table->string('codigo')->nullable()->default(null);
            $table->double('precio')->nullable()->default(null);
            $table->double('cantidad')->nullable()->default(null);
            $table->double('total')->nullable()->default(null);
            $table->string('token')->nullable()->default(null);
            $table->string('ern')->nullable()->default(null);
            $table->timestamp('fechaAprobacion')->nullable()->default(null);
            $table->string('fechaAprobacionS')->nullable()->default(null);
            $table->string('aprobacion')->nullable()->default(null);
            $table->string('descripcion')->nullable()->default(null);
            $table->double('latitud',15,8)->nullable()->default(null);
            $table->double('longitud',15,8)->nullable()->default(null);
            $table->integer('type')->nullable()->default(1);
            $table->integer('state')->nullable()->default(1);

            $table->integer('usuario')->nullable()->default(null)->unsigned();
            $table->foreign('usuario')->references('id')->on('users')->onDelete('cascade');

            $table->integer('evento')->nullable()->default(null)->unsigned();
            $table->foreign('evento')->references('id')->on('eventos')->onDelete('cascade');

            $table->integer('evento_funcion')->nullable()->default(null)->unsigned();
            $table->foreign('evento_funcion')->references('id')->on('eventos_funciones')->onDelete('cascade');

            $table->integer('evento_funcion_area_lugar')->nullable()->default(null)->unsigned();
            $table->foreign('evento_funcion_area_lugar')->references('id')->on('eventos_funciones_area_lugar')->onDelete('cascade');

            $table->integer('evento_vendedor')->nullable()->default(null)->unsigned();
            $table->foreign('evento_vendedor')->references('id')->on('eventos_vendedor')->onDelete('cascade');

            $table->integer('evento_descuento')->nullable()->default(null)->unsigned();
            $table->foreign('evento_descuento')->references('id')->on('eventos_descuento')->onDelete('cascade');

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
        Schema::dropIfExists('eventos_venta');
    }
}
