@extends('layouts.user')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-emerald-900">👋 Bienvenido, {{ Auth::user()->name }}</h1>
            <p class="text-emerald-700 mt-2">Aquí está el resumen de tus productos</p>
        </div>

        <!-- Estadística Principal -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-700 text-sm font-medium">📦 Mis Productos</p>
                    <p class="text-4xl font-bold text-emerald-900 mt-3">{{ $misProductos }}</p>
                </div>
                <div class="text-6xl opacity-20">📦</div>
            </div>
            <div class="mt-4">
                <a href="{{ route('productos.index') }}" class="inline-block bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-2 px-6 rounded-lg transition">
                    Ver Todos mis Productos →
                </a>
            </div>
        </div>

        <!-- Mis Últimos Productos -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-emerald-900 mb-6 flex items-center gap-2">
                <span>⏱️</span> Mis Últimos Productos
            </h2>

            @if ($ultimosProductos->count() > 0)
                <div class="space-y-4">
                    @foreach ($ultimosProductos as $producto)
                        <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-lg border border-emerald-200 hover:bg-emerald-100 transition">
                            <div>
                                <h3 class="font-semibold text-emerald-900 text-lg">{{ $producto->name }}</h3>
                                <p class="text-sm text-emerald-700 mt-1">{{ Str::limit($producto->description, 100) }}</p>
                                <p class="text-xs text-emerald-600 mt-2">
                                    📅 {{ $producto->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('productos.show', $producto) }}" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                    Ver
                                </a>
                                <a href="{{ route('productos.edit', $producto) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                    Editar
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">📭</div>
                    <p class="text-emerald-700 font-medium mb-4">Aún no has creado productos</p>
                    <a href="{{ route('productos.create') }}" class="inline-block bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-2 px-8 rounded-lg transition">
                        Crear mi Primer Producto
                    </a>
                </div>
            @endif
        </div>

        <!-- Información Útil -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-amber-50 rounded-lg p-6 border-l-4 border-amber-500">
                <h3 class="text-lg font-semibold text-amber-900 mb-3">💡 Consejos</h3>
                <ul class="text-sm text-amber-800 space-y-2">
                    <li>✅ Cada producto debe tener un nombre único</li>
                    <li>✅ Agrega una descripción clara del producto</li>
                    <li>✅ Selecciona la categoría correcta</li>
                    <li>✅ Puedes editar tus productos en cualquier momento</li>
                </ul>
            </div>

            <div class="bg-emerald-50 rounded-lg p-6 border-l-4 border-emerald-500">
                <h3 class="text-lg font-semibold text-emerald-900 mb-3">🔐 Permisos</h3>
                <ul class="text-sm text-emerald-800 space-y-2">
                    <li>✅ Ver solo tus productos</li>
                    <li>✅ Crear nuevos productos</li>
                    <li>✅ Editar tus productos</li>
                    <li>✅ Eliminar tus productos</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
