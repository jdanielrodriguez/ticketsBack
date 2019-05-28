<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosVendedorMensajeriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos_vendedor_mensajeria', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo')->nullable()->default(null);
            $table->string('mensaje')->nullable()->default(null);
            $table->timestamp('fecha')->useCurrent();
            $table->string('sujeto')->nullable()->default(null);
            $table->string('descripcion')->nullable()->default(null);
            $table->integer('type')->nullable()->default(1);
            $table->integer('state')->nullable()->default(1);

            $table->integer('envia')->nullable()->default(null)->unsigned();
            $table->foreign('envia')->references('id')->on('users')->onDelete('cascade');

            $table->integer('recibe')->nullable()->default(null)->unsigned();
            $table->foreign('recibe')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('eventos_vendedor_mensajeria');
    }
}
