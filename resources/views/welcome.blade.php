<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
            <!-- Navbar -->
            <nav class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <a href="{{ route('index') }}" class="text-2xl font-bold text-blue-600">
                                📦 Inventory
                            </a>
                        </div>
                        <div class="flex items-center space-x-4">
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">
                                    Login
                                </a>
                            @endif

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                    Register
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </nav>

            <div class="py-12 px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <!-- Header -->
                    <div class="text-center mb-12">
                        <h1 class="text-5xl font-bold text-gray-900 mb-4">📦 Sistema de Inventario</h1>
                        <p class="text-xl text-gray-600">Gestiona tus productos de forma fácil y eficiente</p>
                    </div>

                    <!-- Main Grid -->
                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Productos Card -->
                        <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                            <div class="flex items-center mb-4">
                                <div class="text-4xl mr-4">📊</div>
                                <h2 class="text-2xl font-bold text-gray-900">Gestión de Productos</h2>
                            </div>
                            <p class="text-gray-600 mb-6">Administra el catálogo de productos del inventario.</p>
                            <div class="space-y-3">
                                <a href="{{ route('productos.index') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg text-center transition">
                                    Ver Productos
                                </a>
                                <a href="{{ route('productos.create') }}" class="block w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg text-center transition">
                                    Crear Producto
                                </a>
                            </div>
                        </div>

                        <!-- Info Card -->
                        <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                            <div class="flex items-center mb-4">
                                <div class="text-4xl mr-4">ℹ️</div>
                                <h2 class="text-2xl font-bold text-gray-900">Información</h2>
                            </div>
                            <p class="text-gray-600 mb-6">Sistema completo para la gestión de tu inventario.</p>
                            <div class="space-y-2 text-gray-700">
                                <p><strong>✓</strong> Crear y editar productos</p>
                                <p><strong>✓</strong> Gestionar stock</p>
                                <p><strong>✓</strong> Control de precios</p>
                                <p><strong>✓</strong> Eliminar productos</p>
                            </div>
                        </div>
                    </div>

                    <!-- Features Section -->
                    <div class="mt-12 bg-white rounded-lg shadow-lg p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Funcionalidades Principales</h3>
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="border-l-4 border-blue-600 pl-4">
                                <h4 class="font-bold text-gray-900 mb-2">📋 Listado Paginado</h4>
                                <p class="text-gray-600 text-sm">Visualiza todos tus productos con paginación automática</p>
                            </div>
                            <div class="border-l-4 border-green-600 pl-4">
                                <h4 class="font-bold text-gray-900 mb-2">➕ Crear Producto</h4>
                                <p class="text-gray-600 text-sm">Agrega nuevos productos rápidamente con validaciones</p>
                            </div>
                            <div class="border-l-4 border-yellow-600 pl-4">
                                <h4 class="font-bold text-gray-900 mb-2">✏️ Editar Producto</h4>
                                <p class="text-gray-600 text-sm">Modifica cualquier información del producto</p>
                            </div>
                            <div class="border-l-4 border-red-600 pl-4">
                                <h4 class="font-bold text-gray-900 mb-2">🗑️ Eliminar Producto</h4>
                                <p class="text-gray-600 text-sm">Elimina productos con confirmación de seguridad</p>
                            </div>
                            <div class="border-l-4 border-purple-600 pl-4">
                                <h4 class="font-bold text-gray-900 mb-2">Ver Detalles</h4>
                                <p class="text-gray-600 text-sm">Visualiza la información completa de cada producto</p>
                            </div>
                            <div class="border-l-4 border-indigo-600 pl-4">
                                <h4 class="font-bold text-gray-900 mb-2">🔍 Validación de Datos</h4>
                                <p class="text-gray-600 text-sm">Campos validados con reglas de negocio</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

