<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ProductoController;

// Ruta principal - Welcome
Route::get('/', [HomeController::class, 'index'])->name('index');

// Rutas del controlador de productos (CRUD completo)
Route::resource('productos', ProductoController::class);
