<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductImageRequest;
use App\Models\Producto;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    /**
     * Store an image for a product
     */
    public function store(StoreProductImageRequest $request, Producto $producto)
    {
        // Autorización primero - sin catch
        $this->authorize('update', $producto);

        try {
            // La validación ya está hecha por StoreProductImageRequest
            $validated = $request->validated();

            // Validar que el archivo fue realmente enviado
            if (!$request->hasFile('image') || !$request->file('image')->isValid()) {
                return back()->withErrors(['image' => 'El archivo no es válido. Por favor, intenta nuevamente.']);
            }

            $file = $request->file('image');

            // Crear el directorio si no existe (Laravel lo hace automáticamente)
            $storagePath = "productos/{$producto->id}";

            // Almacenar la imagen
            $path = $file->store($storagePath, 'public');

            if (!$path) {
                return back()->withErrors(['image' => 'No se pudo guardar la imagen. Verifica los permisos del servidor.']);
            }

            // Si no hay imagen primaria, hacer que esta sea la primera
            $isPrimary = !$producto->images()->where('is_primary', true)->exists();

            ProductImage::create([
                'producto_id' => $producto->id,
                'user_id' => auth()->id(),
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'is_primary' => $isPrimary
            ]);

            return back()->with('success', 'Imagen agregada exitosamente.');
        } catch (\Exception $e) {
            \Log::error('Error al subir imagen: ' . $e->getMessage(), [
                'producto_id' => $producto->id,
                'user_id' => auth()->id(),
                'exception' => $e
            ]);
            return back()->withErrors(['image' => 'Error al subir la imagen. Por favor intenta nuevamente.']);
        }
    }

    /**
     * Set an image as primary
     */
    public function setPrimary(ProductImage $image)
    {
        $this->authorize('update', $image->producto);

        // Remover imagen primaria anterior
        $image->producto->images()->update(['is_primary' => false]);

        // Establecer esta como primaria
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Imagen principal actualizada.');
    }

    /**
     * Delete an image
     */
    public function destroy(ProductImage $image)
    {
        $this->authorize('update', $image->producto);

        // Eliminar del almacenamiento
        Storage::disk('public')->delete($image->path);

        // Si era la imagen primaria, establecer otra como primaria
        if ($image->is_primary) {
            $nextImage = $image->producto->images()->first();
            if ($nextImage) {
                $nextImage->update(['is_primary' => true]);
            }
        }

        $image->delete();

        return back()->with('success', 'Imagen eliminada exitosamente.');
    }
}
