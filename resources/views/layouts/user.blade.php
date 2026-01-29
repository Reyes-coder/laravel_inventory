<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Mi Panel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles

        <style>
            :root {
                --user-primary: #065f46;
                --user-secondary: #10b981;
                --user-accent: #f59e0b;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-emerald-50">
        <x-banner />

        <div class="min-h-screen">
            @livewire('navigation-menu')

            <!-- Sidebar User -->
            <div class="flex">
                <div class="w-64 bg-emerald-600 shadow-lg min-h-screen p-6">
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                            <span class="text-2xl">👤</span>
                            Mi Panel
                        </h3>
                        <p class="text-emerald-100 text-sm mt-2">Usuario: <span class="font-semibold">{{ auth()->user()->name }}</span></p>
                    </div>

                    <nav class="space-y-3">
                        <a href="{{ route('productos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('productos.*') ? 'bg-white bg-opacity-20 text-white font-semibold' : 'text-emerald-100 hover:bg-white hover:bg-opacity-10' }} transition">
                            <span class="text-lg">📦</span>
                            <span class="font-medium">Mis Productos</span>
                        </a>

                        <div class="border-t border-emerald-500 pt-3 mt-3">
                            <p class="text-xs font-semibold text-emerald-100 px-4 mb-2">ACCIONES</p>
                            <a href="{{ route('productos.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-emerald-100 hover:bg-white hover:bg-opacity-10 transition">
                                <span class="text-lg">➕</span>
                                <span class="font-medium">Crear Producto</span>
                            </a>
                        </div>

                        <div class="border-t border-emerald-500 pt-3">
                            <p class="text-xs font-semibold text-emerald-100 px-4 mb-2">CUENTA</p>
                            <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-emerald-100 hover:bg-white hover:bg-opacity-10 transition">
                                <span class="text-lg">⚙️</span>
                                <span class="font-medium">Perfil</span>
                            </a>
                        </div>
                    </nav>
                </div>

                <!-- Main Content -->
                <div class="flex-1">
                    <!-- Page Heading -->
                    @if (isset($header))
                        <header class="bg-white border-b border-emerald-200 shadow-md">
                            <div class="px-4 sm:px-6 lg:px-8 py-6">
                                <div class="flex items-center gap-3 text-emerald-900">
                                    <span class="text-3xl">✨</span>
                                    <div>{{ $header }}</div>
                                </div>
                            </div>
                        </header>
                    @endif

                    <!-- Page Content -->
                    <main class="min-h-screen">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
