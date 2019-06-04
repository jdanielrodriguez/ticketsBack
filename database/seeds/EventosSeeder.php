<?php

use Illuminate\Database\Seeder;

class EventosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('eventos')->insert([
            'titulo'       => 'Conferencia con Jurgen',
            'descripcion'       => 'Conferencia',
            'type'       => 1,
            'state'       => 1,
            'usuario'       => 2,
            'categoria'       => 3,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('eventos_funciones')->insert([
            'titulo'       => 'Conferencia con Jurgen',
            'imagen'       => 'https://i.ytimg.com/vi/EO2h32CA10E/maxresdefault.jpg',
            'descripcion'       => 'Conferencia con Jurgen Klaric',
            'direccion'       => 'Conferencia',
            'hora_inicio'       => '15:00:00',
            'hora_fin'       => '21:00:00',
            'fecha_inicio'       => '2019-06-21',
            'fecha_fin'       => '2019-06-21',
            'inicio'       => '2019-06-21 15:00:00',
            'fin'       => '2019-06-21 21:00:00',
            'latitud'       => 0,
            'longitud'       => 0,
            'type'       => 2,
            'state'       => 1,
            'evento'       => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);





        DB::table('eventos_vendedor')->insert([
            'titulo'          =>  "godoy-alejandro@hotmail.com",
            'porcentaje'          => 15,
            'cantidad'             => null,
            'descripcion'         => "Descuento de alejandro",
            'type'          => 1,
            'state'       => 1,
            'usuario'          => 3,
            'usuario_admin'             => 2,
            'evento_funcion'             => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);

        DB::table('eventos_funciones_area')->insert([
            'titulo'       => 'Taller',
            'descripcion'       => 'Taller sin Jurgen Klaric',
            'precio'       => '89',
            'total'       => '100',
            'vendidos'       => '0',
            'type'       => 1,
            'state'       => 1,
            'evento_funcion'       => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);
        for ($i=0; $i < 100; $i++) { 
            DB::table('eventos_funciones_area_lugar')->insert([
                'titulo'       => 'Silla',
                'descripcion'       => null,
                'lugar'       => $i+1,
                'butaca'       => $i+1,
                'numero'       => $i+1,
                'vendido'       => '0',
                'type'       => 1,
                'state'       => 1,
                'evento_funcion_area'       => 1,
                'created_at'        => date('Y-m-d H:m:s'),
                'updated_at'        => date('Y-m-d H:m:s')
            ]);
        }
        

        DB::table('eventos_funciones_area')->insert([
            'titulo'       => 'Taller y Jurgen Klaric preferencia',
            'descripcion'       => 'Taller y preferencia con Jurgen Klaric',
            'precio'       => '110',
            'total'       => '50',
            'vendidos'       => '0',
            'type'       => 1,
            'state'       => 1,
            'evento_funcion'       => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);
        for ($i=0; $i < 100; $i++) { 
            DB::table('eventos_funciones_area_lugar')->insert([
                'titulo'       => 'Silla',
                'descripcion'       => null,
                'lugar'       => $i+1,
                'butaca'       => $i+1,
                'numero'       => $i+1,
                'vendido'       => '0',
                'type'       => 1,
                'state'       => 1,
                'evento_funcion_area'       => 2,
                'created_at'        => date('Y-m-d H:m:s'),
                'updated_at'        => date('Y-m-d H:m:s')
            ]);
        }



        DB::table('eventos_funciones_area')->insert([
            'titulo'       => 'Taller y Jurgen Klaric oro',
            'descripcion'       => 'Taller y oro con Jurgen Klaric',
            'precio'       => '135',
            'total'       => '25',
            'vendidos'       => '0',
            'type'       => 1,
            'state'       => 1,
            'evento_funcion'       => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);
        for ($i=0; $i < 30; $i++) { 
            DB::table('eventos_funciones_area_lugar')->insert([
                'titulo'       => 'Silla',
                'descripcion'       => null,
                'lugar'       => $i+1,
                'butaca'       => $i+1,
                'numero'       => $i+1,
                'vendido'       => '0',
                'type'       => 1,
                'state'       => 1,
                'evento_funcion_area'       => 3,
                'created_at'        => date('Y-m-d H:m:s'),
                'updated_at'        => date('Y-m-d H:m:s')
            ]);
        }



        DB::table('eventos_funciones_area')->insert([
            'titulo'       => 'Taller y Jurgen Klaric VIP',
            'descripcion'       => 'Taller y VIP con Jurgen Klaric',
            'precio'       => '335',
            'total'       => '15',
            'vendidos'       => '0',
            'type'       => 1,
            'state'       => 1,
            'evento_funcion'       => 1,
            'created_at'        => date('Y-m-d H:m:s'),
            'updated_at'        => date('Y-m-d H:m:s')
        ]);
        for ($i=0; $i < 20; $i++) { 
            DB::table('eventos_funciones_area_lugar')->insert([
                'titulo'       => 'Silla',
                'descripcion'       => null,
                'lugar'       => $i+1,
                'butaca'       => $i+1,
                'numero'       => $i+1,
                'vendido'       => '0',
                'type'       => 1,
                'state'       => 1,
                'evento_funcion_area'       => 4,
                'created_at'        => date('Y-m-d H:m:s'),
                'updated_at'        => date('Y-m-d H:m:s')
            ]);
        }
    }
}
