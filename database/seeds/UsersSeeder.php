<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('roles')->insert([
            'titulo'       => 'Administrador',
            'descripcion'       => 'Administrador del sistema',
            'state'       => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('users')->insert([
            "id"                => 1,
            'username'          =>  "admin",
            'password'          => bcrypt('1234'),
            'email'             => "jdanielr61@gmail.com",
            'nombres'         => "Admin",
            'apellidos'          => "Sys",
            'descripcion'       => "",
            'nacimiento'          => "1995-01-06",
            'state'             => 1,
            'rol'             => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);
    }
}