<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ProductoController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Middleware\EnsureUserIsAuthenticated;

// Ruta principal - Welcome (pública)
Route::get('/', [HomeController::class, 'index'])->name('index');

// Rutas del controlador de productos protegidas (requieren autenticación)
Route::middleware([EnsureUserIsAuthenticated::class, config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::resource('productos', ProductoController::class);
});

Route::middleware([
    EnsureUserIsAuthenticated::class,
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
