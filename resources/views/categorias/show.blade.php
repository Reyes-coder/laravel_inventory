<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ver Categoría: ') }} {{ $categoria->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $categoria->name }}</h3>
                    <p class="text-sm text-gray-600">
                        <strong>Slug:</strong> {{ $categoria->slug }}
                    </p>
                </div>

                @if($categoria->description)
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-700 mb-2">Descripción</h4>
                        <p class="text-gray-600">{{ $categoria->description }}</p>
                    </div>
                @endif

                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-700 mb-2">Productos en esta categoría</h4>
                    @if($categoria->productos->count())
                        <ul class="list-disc list-inside space-y-2">
                            @foreach($categoria->productos as $producto)
                                <li class="text-gray-600">
                                    <a href="{{ route('productos.show', $producto) }}" class="text-blue-600 hover:text-blue-900">
                                        {{ $producto->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-600">No hay productos en esta categoría.</p>
                    @endif
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('categorias.edit', $categoria) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition">
                        Editar
                    </a>
                    <form method="POST" action="{{ route('categorias.destroy', $categoria) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition" onclick="return confirm('¿Está seguro de que desea eliminar esta categoría?')">
                            Eliminar
                        </button>
                    </form>
                    <a href="{{ route('categorias.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition">
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
