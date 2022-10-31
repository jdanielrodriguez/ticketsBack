<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCajaDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caja_detalle', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo')->nullable()->default(null);
            $table->string('descripcion')->nullable()->default(null);
            $table->double('precio',5,2)->nullable()->default(null);
            $table->double('haber',5,2)->nullable()->default(null);
            $table->double('plataforma',5,5)->nullable()->default(null);
            $table->double('comision',5,5)->nullable()->default(null);
            $table->double('descuento',5,2)->nullable()->default(null);
            $table->double('egreso',5,2)->nullable()->default(null);
            $table->double('porcentaje_descuento',5,5)->nullable()->default(null);
            $table->double('iva',5,5)->nullable()->default(null);
            $table->double('total',5,2)->nullable()->default(null);
            $table->integer('type')->nullable()->default(1);
            $table->integer('state')->nullable()->default(1);
            $table->integer('retiro')->nullable()->default(1);

            $table->integer('cupon_descuento')->nullable()->default(null)->unsigned();
            $table->foreign('cupon_descuento')->references('id')->on('users')->onDelete('cascade');

            $table->integer('comprador')->nullable()->default(null)->unsigned();
            $table->foreign('comprador')->references('id')->on('users')->onDelete('cascade');

            $table->integer('caja')->nullable()->default(null)->unsigned();
            $table->foreign('caja')->references('id')->on('caja')->onDelete('cascade');

            $table->integer('evento_funcion_area_lugar')->nullable()->default(null)->unsigned();
            $table->foreign('evento_funcion_area_lugar')->references('id')->on('eventos_funciones_area_lugar')->onDelete('cascade');

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
        Schema::dropIfExists('caja_detalle');
    }
}
