<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsuariosTableSeeder::class,
            PiezasTableSeeder::class,
            PedidosTableSeeder::class,
            DetallesPedidosTableSeeder::class,
        ]);
    }
}
