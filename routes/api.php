<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductoController;

// Rutas protegidas con Sanctum
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Rutas de Productos - SIN PROTECCIÓN (públicas) con nombres diferentes
Route::apiResource('productos', ProductoController::class)->names([
    'index' => 'api.productos.index',
    'show' => 'api.productos.show',
    'store' => 'api.productos.store',
    'update' => 'api.productos.update',
    'destroy' => 'api.productos.destroy',
]);
