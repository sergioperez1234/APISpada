<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetallesPedidosTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('detallesPedidos')->insert([
            [
                'idPedido' => 1, // Asegúrate de que este ID exista en la tabla pedidos
                'idPieza' => 1,  // Asegúrate de que este ID exista en la tabla piezas
                'cantidad' => 2,
            ],
            [
                'idPedido' => 1, // Asegúrate de que este ID exista en la tabla pedidos
                'idPieza' => 2,  // Asegúrate de que este ID exista en la tabla piezas
                'cantidad' => 1,
            ],
            [
                'idPedido' => 2, // Asegúrate de que este ID exista en la tabla pedidos
                'idPieza' => 1,  // Asegúrate de que este ID exista en la tabla piezas
                'cantidad' => 4,
            ],
        ]);
    }
}
