<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Productos') }}
            </h2>
            <a href="{{ route('productos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                + Crear Producto
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Bar -->
            <div class="mb-6 bg-white rounded-lg shadow-md p-6">
                <form method="GET" action="{{ route('productos.index') }}" class="flex gap-4">
                    <div class="flex-1">
                        <input
                            type="text"
                            name="search"
                            placeholder="Buscar por nombre, descripción o categoría..."
                            value="{{ $search }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>
                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition"
                    >
                        🔍 Buscar
                    </button>
                    @if($search)
                        <a
                            href="{{ route('productos.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition"
                        >
                            Limpiar
                        </a>
                    @endif
                </form>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                @if($productos->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Categoría</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Precio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($productos as $producto)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $producto->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ $producto->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $producto->category ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">${{ number_format($producto->price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-3 py-1 {{ $producto->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full text-xs font-semibold">
                                            {{ $producto->stock }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($producto->active)
                                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Activo</span>
                                        @else
                                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                        <a href="{{ route('productos.show', $producto->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Ver</a>
                                        <a href="{{ route('productos.edit', $producto->id) }}" class="text-yellow-600 hover:text-yellow-800 font-semibold">Editar</a>
                                        <button onclick="confirmDelete({{ $producto->id }})" class="text-red-600 hover:text-red-800 font-semibold">Eliminar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="px-6 py-4 bg-white border-t border-gray-200">
                        {{ $productos->links() }}
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        @if($search)
                            <p class="text-gray-500 text-lg">No se encontraron productos que coincidan con "<strong>{{ $search }}</strong>"</p>
                            <a href="{{ route('productos.index') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                                Ver todos los productos
                            </a>
                        @else
                            <p class="text-gray-500 text-lg">No hay productos registrados</p>
                            <a href="{{ route('productos.create') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                                Crear el primer producto
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold text-gray-900">Confirmar eliminación</h3>
            <p class="mt-2 text-gray-600">¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.</p>
            <div class="mt-6 flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300 font-semibold">
                    Cancelar
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 font-semibold">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function confirmDelete(productId) {
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteForm').action = `/productos/${productId}`;
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    window.addEventListener('click', function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target === modal) {
            closeDeleteModal();
        }
    });
    </script>
</x-app-layout>
