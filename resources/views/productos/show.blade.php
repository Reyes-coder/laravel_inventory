<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $producto->name }}
            </h2>
            <div class="space-x-3">
                @can('update', $producto)
                    <a href="{{ route('productos.edit', $producto->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded inline-block">
                        Editar
                    </a>
                @endcan
                @can('delete', $producto)
                    <button onclick="confirmDelete({{ $producto->id }})" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Eliminar
                    </button>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <a href="{{ route('productos.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold mb-6 inline-block">
                ← Volver al listado
            </a>

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

                <!-- Product Images Section -->
                <div class="mt-8 border-t pt-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">📸 Galería de Imágenes</h2>

                    <!-- Upload Form -->
                    @can('update', $producto)
                    <div class="bg-blue-50 border-2 border-dashed border-blue-300 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Subir Nueva Imagen</h3>
                        <form action="{{ route('product-images.store', $producto->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div class="flex items-center justify-center w-full">
                                <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-blue-300 border-dashed rounded-lg cursor-pointer bg-blue-50 hover:bg-blue-100 transition">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-10 h-10 text-blue-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="text-sm text-gray-700"><span class="font-semibold">Haz clic para subir</span> o arrastra una imagen</p>
                                        <p class="text-xs text-gray-600 mt-1">PNG, JPG, GIF, SVG (máx. 2MB)</p>
                                    </div>
                                    <input type="file" name="image" class="hidden" accept="image/*" required onchange="previewImage(this)">
                                </label>
                            </div>

                            <div id="imagePreview" class="hidden">
                                <img id="previewImg" src="" alt="Preview" class="max-h-40 rounded-lg mx-auto">
                            </div>

                            @error('image')
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="flex justify-end space-x-3">
                                <button type="reset" class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300 font-semibold">
                                    Limpiar
                                </button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold">
                                    ✓ Subir Imagen
                                </button>
                            </div>
                        </form>
                    </div>
                    @endcan

                    <!-- Images Gallery -->
                    <div>
                        @if($producto->images->count() > 0)
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $producto->images->count() }} imagen(es) subida(s)</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($producto->images as $image)
                                    <div class="relative group bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                                        <!-- Image Container -->
                                        <div class="relative overflow-hidden bg-gray-100 h-40">
                                            @if(file_exists(public_path('storage/' . $image->path)))
                                                <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $image->original_name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                    <span class="text-gray-500 text-sm">Imagen no encontrada</span>
                                                </div>
                                            @endif

                                            <!-- Primary Badge -->
                                            @if($image->is_primary)
                                                <div class="absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                                    ⭐ Principal
                                                </div>
                                            @endif

                                            <!-- Actions Overlay -->
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                <div class="flex space-x-2">
                                                    @can('update', $producto)
                                                        @if(!$image->is_primary)
                                                            <form action="{{ route('product-images.set-primary', $image->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-full transition" title="Establecer como principal">
                                                                    ⭐
                                                                </button>
                                                            </form>
                                                        @endif

                                                        <button onclick="confirmDeleteImage({{ $image->id }})" class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full transition" title="Eliminar">
                                                            🗑️
                                                        </button>
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Image Info -->
                                        <div class="p-3">
                                            <p class="text-xs text-gray-600 truncate">{{ $image->original_name }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $image->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-lg p-8 text-center border-2 border-dashed border-gray-300">
                                <p class="text-gray-600 text-lg mb-2">📷 No hay imágenes para este producto</p>
                                @can('update', $producto)
                                    <p class="text-gray-500 text-sm">Sube la primera imagen usando el formulario arriba</p>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>

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
                        @if(auth()->user()->isAdmin())
                            <div>
                                <p class="font-semibold">Propietario</p>
                                <p class="text-gray-900 font-medium">{{ $producto->user?->name ?? 'Desconocido' }}</p>
                            </div>
                        @endif
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
</x-app-layout>
