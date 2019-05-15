<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->nullable()->default(null);
            $table->string('password');
            $table->string('email');
            $table->string('nombres')->nullable()->default(null);
            $table->string('apellidos')->nullable()->default(null);
            $table->string('descripcion')->nullable()->default(null);
            $table->string('nacimiento')->nullable()->default(null);
            $table->integer('state')->nullable()->default(1);

            $table->integer('rol')->nullable()->default(null)->unsigned();
            $table->foreign('rol')->references('id')->on('roles')->onDelete('cascade');
            
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
