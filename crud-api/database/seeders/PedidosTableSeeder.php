<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PedidosTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('pedidos')->insert([
            [
                'idComprador' => 1, // Asegúrate de que este ID exista en la tabla usuarios
                'precio' => 30
            ],
            [
                'idComprador' => 2, // Asegúrate de que este ID exista en la tabla usuarios
                'precio' => 60
            ],
        ]);
    }
}
