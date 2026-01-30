<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // Dashboard para ADMIN - con estadísticas de todos los usuarios
            $totalProducts = Producto::count();
            $totalUsers = User::where('role', 'user')->count();
            $adminCount = User::where('role', 'admin')->count();

            // Productos por usuario
            $productosPorUsuario = User::withCount('productos')
                ->where('role', 'user')
                ->get();

            // Últimas actividades (últimos productos creados)
            $ultimosProductos = Producto::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return view('dashboard-admin', compact(
                'totalProducts',
                'totalUsers',
                'adminCount',
                'productosPorUsuario',
                'ultimosProductos'
            ));
        } else {
            // Dashboard para USER - solo sus estadísticas
            $misProductos = $user->productos()->count();
            $ultimosProductos = $user->productos()
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return view('dashboard-user', compact('misProductos', 'ultimosProductos'));
        }
    }
}
