@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">📊 Panel de Administrador</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-2">Monitoreo de actividades y estadísticas del sistema</p>
        </div>

        <!-- Grid de estadísticas principales -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total de Productos -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-medium">📦 Total de Productos</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ $totalProducts }}</p>
                    </div>
                    <div class="text-4xl text-blue-500 opacity-20">📦</div>
                </div>
            </div>

            <!-- Total de Usuarios -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-medium">👥 Usuarios Activos</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ $totalUsers }}</p>
                    </div>
                    <div class="text-4xl text-green-500 opacity-20">👥</div>
                </div>
            </div>

            <!-- Total de Admins -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-medium">👑 Administradores</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ $adminCount }}</p>
                    </div>
                    <div class="text-4xl text-red-500 opacity-20">👑</div>
                </div>
            </div>
        </div>

        <!-- Productos por Usuario -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-6 flex items-center gap-2">
                <span>📈</span> Productos por Usuario
            </h2>

            @if ($productosPorUsuario->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-700">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900 dark:text-slate-100">Nombre del Usuario</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900 dark:text-slate-100">Email</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-slate-900 dark:text-slate-100">Productos Creados</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productosPorUsuario as $user)
                                <tr class="border-b border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                                    <td class="px-6 py-4 text-sm text-slate-900 dark:text-slate-100 font-medium">
                                        👤 {{ $user->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-block bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-100 px-3 py-1 rounded-full text-sm font-semibold">
                                            {{ $user->productos_count }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-slate-600 dark:text-slate-400">No hay usuarios registrados aún</p>
                </div>
            @endif
        </div>

        <!-- Últimas Actividades -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-6 flex items-center gap-2">
                <span>⚡</span> Últimos Productos Creados
            </h2>

            @if ($ultimosProductos->count() > 0)
                <div class="space-y-4">
                    @foreach ($ultimosProductos as $producto)
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-600 transition">
                            <div>
                                <h3 class="font-semibold text-slate-900 dark:text-slate-100">{{ $producto->name }}</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                    👤 Creado por: <span class="font-medium">{{ $producto->user->name }}</span>
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">
                                    📅 {{ $producto->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-100 rounded-full text-sm font-medium">
                                    {{ $producto->category }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-slate-600 dark:text-slate-400">No hay productos creados aún</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
