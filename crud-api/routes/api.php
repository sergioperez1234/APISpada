<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\detallesPedidosController;
use App\Http\Controllers\pedidosController;
use App\Http\Controllers\piezasController;
use App\Http\Controllers\AuthController;

//metodos index
Route::get('/usuarios', [UsuarioController::class, 'index']); // Obtener todos los usuarios

Route::get('/detallesPedidos', [detallesPedidosController::class, 'index']); // Obtener todos los detalles de pedido

Route::get('/pedidos', [pedidosController::class, 'index']); // Obtener todos los pedidos

Route::get('/piezas', [piezasController::class, 'index']); // Obtener todas las piezas

//metodos show

Route::get('/usuarios/{id}', [UsuarioController::class, 'show']); // Obtener un usuario

Route::get('/detallesPedidos/{id}', [detallesPedidosController::class, 'show']); // Obtener un detalle de pedido

Route::get('/pedidos/{id}', [pedidosController::class, 'show']); // Obtener un pedido

Route::get('/piezas/{id}', [piezasController::class, 'show']); // Obtener una pieza

//metodos store

Route::post('/usuarios', [UsuarioController::class, 'store']); // Crear un usuario

Route::post('/detallesPedidos', [detallesPedidosController::class, 'store']); // Crear un detalle de pedido

Route::post('/pedidos', [pedidosController::class, 'store']); // Crear un pedido

Route::post('/piezas', [piezasController::class, 'store']); // Crear una pieza

//metodos update

Route::put('/usuarios/{id}', [UsuarioController::class, 'update']); // Actualizar un usuario

Route::put('/detallesPedidos/{id}', [detallesPedidosController::class, 'update']); // Actualizar un detalle de pedido

Route::put('/pedidos/{id}', [pedidosController::class, 'update']); // Actualizar un pedido

Route::put('/piezas/{id}', [piezasController::class, 'update']); // Actualizar una pieza

//metodos destroy

Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']); // Eliminar un usuario

Route::delete('/detallesPedidos/{id}', [detallesPedidosController::class, 'destroy']); // Eliminar un detalle de pedido

Route::delete('/pedidos/{id}', [pedidosController::class, 'destroy']); // Eliminar un pedido

Route::delete('/piezas/{id}', [piezasController::class, 'destroy']); // Eliminar una pieza

//token

Route::post('/register', [AuthController::class, 'register']); // Registrar un usuario
Route::post('/login', [AuthController::class, 'login']); // Autenticar un usuario
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api'); // Cerrar sesión
Route::post('/refresh', [AuthController::class, 'refresh']); // Refrescar el token
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api'); // Obtener información del usuario autenticado
Route::get('/token-attributes', [usuarioController::class, 'getTokenAttributes'])->middleware('auth:api'); // Obtener atributos del token

// Utilidades

Route::post('/filtrado', [piezasController::class, 'search']); // Buscar piezas
