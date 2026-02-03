<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    /**
     * Store an image for a product
     */
    public function store(Request $request, Producto $producto)
    {
        $this->authorize('update', $producto);

        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // Almacenar la imagen
        $path = $request->file('image')->store("productos/{$producto->id}", 'public');

        // Si no hay imagen primaria, hacer que esta sea la primera
        $isPrimary = !$producto->images()->where('is_primary', true)->exists();

        ProductImage::create([
            'producto_id' => $producto->id,
            'path' => $path,
            'original_name' => $request->file('image')->getClientOriginalName(),
            'is_primary' => $isPrimary
        ]);

        return back()->with('success', 'Imagen agregada exitosamente.');
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
