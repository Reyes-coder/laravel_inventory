@props(['header' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Panel Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles

        <style>
            :root {
                --admin-primary: #1e3a8a;
                --admin-secondary: #3b82f6;
                --admin-accent: #ef4444;
            }

            body {
                --tw-bg-opacity: 1;
                background-color: rgb(15 23 42 / var(--tw-bg-opacity));
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-950">
        <x-banner />

        <div class="min-h-screen">
            @livewire('navigation-menu')

            <!-- Sidebar Admin -->
            <div class="flex">
                <div class="w-64 bg-slate-900 shadow-lg min-h-screen p-6 border-r border-slate-800">
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-red-500 flex items-center gap-2">
                            <span class="text-2xl">⚙️</span>
                            Panel Admin
                        </h3>
                        <p class="text-slate-400 text-sm mt-2">Usuario: <span class="font-semibold text-white">{{ auth()->user()->name }}</span></p>
                    </div>

                    <nav class="space-y-3">
                        <a href="{{ route('productos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('productos.*') ? 'bg-red-600 text-white' : 'text-slate-300 hover:bg-slate-800' }} transition">
                            <span class="text-lg">📦</span>
                            <span class="font-medium">Todos los Productos</span>
                        </a>

                        <div class="border-t border-slate-700 pt-3 mt-3">
                            <p class="text-xs font-semibold text-slate-400 px-4 mb-2">OPCIONES</p>
                            <a href="{{ route('productos.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition">
                                <span class="text-lg">➕</span>
                                <span class="font-medium">Crear Producto</span>
                            </a>
                        </div>

                        <div class="border-t border-slate-700 pt-3">
                            <p class="text-xs font-semibold text-slate-400 px-4 mb-2">ADMIN</p>
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 transition">
                                <span class="text-lg">📊</span>
                                <span class="font-medium">Dashboard</span>
                            </a>
                        </div>
                    </nav>
                </div>

                <!-- Main Content -->
                <div class="flex-1">
                    <!-- Page Heading -->
                    @if ($header)
                        <header class="bg-slate-900 border-b border-slate-800 shadow-lg">
                            <div class="px-4 sm:px-6 lg:px-8 py-6">
                                <div class="flex items-center gap-3 text-white">
                                    <span class="text-3xl">🔐</span>
                                    <div>{{ $header }}</div>
                                </div>
                            </div>
                        </header>
                    @endif

                    <!-- Page Content -->
                    <main class="bg-slate-950 min-h-screen">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
