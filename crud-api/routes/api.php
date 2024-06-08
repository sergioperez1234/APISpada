<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\detallesPedidosController;
use App\Http\Controllers\pedidosController;
use App\Http\Controllers\piezasController;
use App\Http\Controllers\AuthController;

//Usuario
Route::get('/usuarios', [UsuarioController::class, 'index']); // Obtener todos los usuarios
Route::get('/usuarios/{id}', [UsuarioController::class, 'show']); // Obtener un usuario
Route::post('/usuarios', [UsuarioController::class, 'store']); // Crear un usuario
Route::put('/usuarios/{id}', [UsuarioController::class, 'update']); // Actualizar un usuario
Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']); // Eliminar un usuario
Route::get('/es-admin', [UsuarioController::class, 'esAdmin']); // Verificar si el usuario es administrador
Route::get('/token-attributes', [UsuarioController::class, 'getTokenAttributes']); // Obtener atributos del token
Route::put('/modificar-usuario/{id}', [UsuarioController::class, 'modificarDatos']); 

//detallesPedidos
Route::get('/detallesPedidos', [detallesPedidosController::class, 'index']); // Obtener todos los detalles de pedido
Route::get('/detallesPedidos/{id}', [detallesPedidosController::class, 'show']); // Obtener un detalle de pedido
Route::post('/detallesPedidos', [detallesPedidosController::class, 'store']); // Crear un detalle de pedido
Route::put('/detallesPedidos/{id}', [detallesPedidosController::class, 'update']); // Actualizar un detalle de pedido
Route::delete('/detallesPedidos/{id}', [detallesPedidosController::class, 'destroy']); // Eliminar un detalle de pedido

//pedidos
Route::get('/pedidos', [pedidosController::class, 'index']); // Obtener todos los pedidos
Route::get('/pedidos/{id}', [pedidosController::class, 'show']); // Obtener un pedido
Route::post('/pedidos', [pedidosController::class, 'store']); // Crear un pedido
Route::put('/pedidos/{id}', [pedidosController::class, 'update']); // Actualizar un pedido
Route::delete('/pedidos/{id}', [pedidosController::class, 'destroy']); // Eliminar un pedido

//piezas
Route::get('/piezas', [piezasController::class, 'index']); // Obtener todas las piezas
Route::get('/piezas/{id}', [piezasController::class, 'show']); // Obtener una pieza
Route::post('/piezas', [piezasController::class, 'store']); // Crear una pieza
Route::put('/piezas/{id}', [piezasController::class, 'update']); // Actualizar una pieza
Route::delete('/piezas/{id}', [piezasController::class, 'destroy']); // Eliminar una pieza

//token

Route::post('/register', [AuthController::class, 'register']); // Registrar un usuario
Route::post('/login', [AuthController::class, 'login']); // Autenticar un usuario
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api'); // Cerrar sesión
// Route::post('/refresh', [AuthController::class, 'refresh']); // Refrescar el token
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api'); // Obtener información del usuario autenticado
Route::get('/token-attributes', [usuarioController::class, 'getTokenAttributes'])->middleware('auth:api'); // Obtener atributos del token
Route::get('/es-admin', [usuarioController::class, 'esAdmin'])->middleware('auth:api'); // Verificar si el usuario es administrador

// Utilidades

Route::post('/filtrado', [piezasController::class, 'search']); // Buscar piezas
