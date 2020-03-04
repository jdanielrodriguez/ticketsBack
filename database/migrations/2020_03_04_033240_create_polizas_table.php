<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePolizasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polizas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion')->nullable()->default(null);
            $table->date('fecha')->nullable()->default(null);
            $table->double('total')->nullable()->default(null);
            $table->date('inicio')->nullable()->default(null);
            $table->date('final')->nullable()->default(null);
            $table->integer('type')->nullable()->default(1);
            $table->integer('state')->nullable()->default(1);

            $table->integer('creador')->nullable()->default(null)->unsigned();
            $table->foreign('creador')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('polizas');
    }
}
