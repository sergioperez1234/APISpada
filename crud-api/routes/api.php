<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\detallesPedidosController;
use App\Http\Controllers\tipoUsuarioController;
use App\Http\Controllers\pedidosController;
use App\Http\Controllers\piezasController;
use App\Http\Controllers\imagenesController;
use App\Http\Controllers\AuthController;

//metodos index
Route::get('/usuarios', [UsuarioController::class, 'index']);

Route::get('/tipoUsuarios', [tipoUsuarioController::class, 'index']);

Route::get('/detallesPedidos', [detallesPedidosController::class, 'index']);

Route::get('/pedidos', [pedidosController::class, 'index']);

Route::get('/piezas', [piezasController::class, 'index']);

Route::get('/imagenes', [imagenesController::class, 'index']);

//metodos show

Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);

Route::get('/tipoUsuarios/{id}', [tipoUsuarioController::class, 'show']);

Route::get('/detallesPedidos/{id}', [detallesPedidosController::class, 'show']);

Route::get('/pedidos/{id}', [pedidosController::class, 'show']);

Route::get('/piezas/{id}', [piezasController::class, 'show']);

Route::get('/imagenes/{id}', [imagenesController::class, 'show']);

//metodos store

Route::post('/usuarios', [UsuarioController::class, 'store']);

Route::post('/tipoUsuarios', [tipoUsuarioController::class, 'store']);

Route::post('/detallesPedidos', [detallesPedidosController::class, 'store']);

Route::post('/pedidos', [pedidosController::class, 'store']);

Route::post('/piezas', [piezasController::class, 'store']);

Route::post('/imagenes', [imagenesController::class, 'store']);

//metodos update

Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);

Route::put('/tipoUsuarios/{id}', [tipoUsuarioController::class, 'update']);

Route::put('/detallesPedidos/{id}', [detallesPedidosController::class, 'update']);

Route::put('/pedidos/{id}', [pedidosController::class, 'update']);

Route::put('/piezas/{id}', [piezasController::class, 'update']);

Route::put('/imagenes/{id}', [imagenesController::class, 'update']);

//metodos destroy

Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);

Route::delete('/tipoUsuarios/{id}', [tipoUsuarioController::class, 'destroy']);

Route::delete('/detallesPedidos/{id}', [detallesPedidosController::class, 'destroy']);

Route::delete('/pedidos/{id}', [pedidosController::class, 'destroy']);

Route::delete('/piezas/{id}', [piezasController::class, 'destroy']);

Route::delete('/imagenes/{id}', [imagenesController::class, 'destroy']);

//token

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');
