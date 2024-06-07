<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PiezasTableSeeder extends Seeder
{
    public function run()
    {
        $piezas = [
            [
                'nombre' => 'Disco de Freno',
                'descripcion' => 'Disco de freno de alta durabilidad.',
                'precio' => 60.00,
                'tipo' => 'Frenos',
                'modelo' => 'DF-200',
                'imagen' => 'images/001.jpg',
            ],
            [
                'nombre' => 'Motor Completo',
                'descripcion' => 'Motor completo con todos los componentes necesarios.',
                'precio' => 2500.00,
                'tipo' => 'Motor',
                'modelo' => 'MC-2021',
                'imagen' => 'images/002.jpg',
            ],
            [
                'nombre' => 'Parabrisas',
                'descripcion' => 'Parabrisas resistente y duradero.',
                'precio' => 200.00,
                'tipo' => 'Parabrisas',
                'modelo' => 'PB-100',
                'imagen' => 'images/003.jpg',
            ],
            [
                'nombre' => 'Bujía de Encendido',
                'descripcion' => 'Bujía de encendido para motor.',
                'precio' => 8.50,
                'tipo' => 'Encendido',
                'modelo' => 'BU-5678',
                'imagen' => 'images/004.jpg',
            ],
            [
                'nombre' => 'Suspensión Completa',
                'descripcion' => 'Sistema de suspensión completo para vehículos.',
                'precio' => 500.00,
                'tipo' => 'Suspensión',
                'modelo' => 'SUS-300',
                'imagen' => 'images/005.jpg',
            ],
            [
                'nombre' => 'Amortiguador Deportivo',
                'descripcion' => 'Amortiguador deportivo para una conducción suave.',
                'precio' => 120.00,
                'tipo' => 'Amortiguador',
                'modelo' => 'AM-456',
                'imagen' => 'images/006.jpg',
            ],
            [
                'nombre' => 'Alerón Deportivo',
                'descripcion' => 'Alerón deportivo para mejorar la aerodinámica.',
                'precio' => 150.00,
                'tipo' => 'Alerón',
                'modelo' => 'AL-200',
                'imagen' => 'images/007.jpg',
            ],
            [
                'nombre' => 'Alerón Trasero',
                'descripcion' => 'Alerón trasero para mejorar la estabilidad del vehículo.',
                'precio' => 175.00,
                'tipo' => 'Alerón',
                'modelo' => 'AL-300',
                'imagen' => 'images/008.jpg',
            ],
            [
                'nombre' => 'Amortiguador Hidráulico',
                'descripcion' => 'Amortiguador hidráulico para mayor comodidad.',
                'precio' => 90.00,
                'tipo' => 'Amortiguador',
                'modelo' => 'AM-789',
                'imagen' => 'images/009.jpg',
            ],
            [
                'nombre' => 'Motor de Alto Rendimiento',
                'descripcion' => 'Motor de alto rendimiento para vehículos deportivos.',
                'precio' => 3200.00,
                'tipo' => 'Motor',
                'modelo' => 'MHR-4000',
                'imagen' => 'images/010.jpg',
            ],
            [
                'nombre' => 'Disco de Freno Ventilado',
                'descripcion' => 'Disco de freno ventilado para mejor refrigeración.',
                'precio' => 75.00,
                'tipo' => 'Frenos',
                'modelo' => 'DFV-150',
                'imagen' => 'images/011.jpg',
            ],
            [
                'nombre' => 'Amortiguador de Gas',
                'descripcion' => 'Amortiguador de gas para mayor rendimiento.',
                'precio' => 110.00,
                'tipo' => 'Amortiguador',
                'modelo' => 'AMG-250',
                'imagen' => 'images/012.jpg',
            ],
            [
                'nombre' => 'Diferencial',
                'descripcion' => 'Diferencial para vehículos de alto rendimiento.',
                'precio' => 850.00,
                'tipo' => 'Diferencial',
                'modelo' => 'DF-600',
                'imagen' => 'images/013.jpg',
            ],
        ];

        DB::table('piezas')->insert($piezas);
    }
}
