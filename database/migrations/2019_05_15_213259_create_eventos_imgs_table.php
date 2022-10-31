<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosImgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos_imgs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo')->nullable()->default(null);
            $table->string('descripcion')->nullable()->default(null);
            $table->string('url')->nullable()->default(null);
            $table->integer('type')->nullable()->default(1);
            $table->integer('state')->nullable()->default(1);

            $table->integer('evento')->nullable()->default(null)->unsigned();
            $table->foreign('evento')->references('id')->on('eventos_funciones')->onDelete('cascade');

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
        Schema::dropIfExists('eventos_imgs');
    }
}
