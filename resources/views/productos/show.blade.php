@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('productos.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold mb-6 inline-block">
            ← Volver al listado
        </a>

        <!-- Header -->
        <div class="mb-8 flex justify-between items-start">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">{{ $producto->name }}</h1>
                <p class="mt-2 text-gray-600">ID: #{{ $producto->id }}</p>
            </div>
            <div class="space-x-3">
                <a href="{{ route('productos.edit', $producto->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded inline-block">
                    Editar
                </a>
                <button onclick="confirmDelete({{ $producto->id }})" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Eliminar
                </button>
            </div>
        </div>

        <!-- Product Details Card -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div>
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-2">Información General</h2>
                        <div class="space-y-4">
                            <div class="border-b pb-4">
                                <label class="text-sm text-gray-600">Nombre del Producto</label>
                                <p class="text-xl font-semibold text-gray-900 mt-1">{{ $producto->name }}</p>
                            </div>
                            <div class="border-b pb-4">
                                <label class="text-sm text-gray-600">SKU</label>
                                <p class="text-lg text-gray-900 mt-1">{{ $producto->sku ?? 'No asignado' }}</p>
                            </div>
                            <div class="border-b pb-4">
                                <label class="text-sm text-gray-600">Categoría</label>
                                <p class="text-lg text-gray-900 mt-1">{{ $producto->category ?? 'Sin categoría' }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-2">Descripción</h2>
                        <div class="bg-gray-50 p-4 rounded border border-gray-200">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $producto->description ?? 'Sin descripción' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="mb-8 bg-blue-50 p-6 rounded-lg border border-blue-200">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Información de Precio</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm text-gray-600">Precio Unitario</label>
                                <p class="text-4xl font-bold text-blue-600 mt-2">${{ number_format($producto->price, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Información de Stock</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm text-gray-600">Cantidad en Stock</label>
                                <div class="flex items-center mt-2">
                                    <p class="text-4xl font-bold {{ $producto->stock > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $producto->stock }}</p>
                                    <span class="ml-4 px-4 py-2 {{ $producto->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full font-semibold text-sm">
                                        {{ $producto->stock > 0 ? 'Disponible' : 'Agotado' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-purple-50 rounded-lg border border-purple-200">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Estado</h2>
                        <div class="flex items-center">
                            @if($producto->active)
                                <span class="px-6 py-2 bg-blue-100 text-blue-700 rounded-full font-semibold text-lg">
                                    ✓ Activo
                                </span>
                            @else
                                <span class="px-6 py-2 bg-gray-100 text-gray-700 rounded-full font-semibold text-lg">
                                    ✗ Inactivo
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="mt-8 border-t pt-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                    <div>
                        <p class="font-semibold">Creado</p>
                        <p>{{ $producto->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="font-semibold">Última actualización</p>
                        <p>{{ $producto->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-bold text-gray-900">Confirmar eliminación</h3>
        <p class="mt-2 text-gray-600">¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.</p>
        <p class="mt-2 text-sm text-gray-500 font-semibold">{{ $producto->name }}</p>
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
@endsection
