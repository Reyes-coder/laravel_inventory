<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventario de Productos')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('index') }}" class="text-2xl font-bold text-gray-900">
                    📦 Inventario
                </a>
                <div class="space-x-4">
                    <a href="{{ route('index') }}" class="text-gray-600 hover:text-gray-900 font-medium">Inicio</a>
                    <a href="{{ route('productos.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">Productos</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2026 Sistema de Inventario. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
