<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuariosTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('usuarios')->insert([
            [
                'nombre' => 'Juan Perez',
                'password' => Hash::make('Admin1234#'),
                'email' => 'admin@gmail.com.com',
                'direccion' => '123 Calle Falsa',
                'telefono' => '123456789',
                'tipoUsuario' => true,
            ],
            [
                'nombre' => 'Maria Gomez',
                'password' => Hash::make('password1234#'),
                'email' => 'user@gmail.com',
                'direccion' => '456 Avenida Siempre Viva',
                'telefono' => '987654321',
                'tipoUsuario' => false,
            ],
        ]);
    }
}