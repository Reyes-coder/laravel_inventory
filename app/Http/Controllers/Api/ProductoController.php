<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductoController extends Controller
{
    /**
     * Display a listing of all products (sin autenticación)
     */
    public function index(): JsonResponse
    {
        $productos = Producto::all();
        return response()->json([
            'success' => true,
            'data' => $productos,
            'message' => 'Productos obtenidos correctamente'
        ]);
    }

    /**
     * Store a newly created product (sin autenticación)
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|string|max:100',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'sku' => 'required|string|unique:productos,sku',
                'active' => 'boolean'
            ]);

            $producto = Producto::create([
                ...$validated,
                'user_id' => 1, // Usuario por defecto (admin)
                'role' => 'admin'
            ]);

            return response()->json([
                'success' => true,
                'data' => $producto,
                'message' => 'Producto creado exitosamente'
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Error de validación'
            ], 422);
        }
    }

    /**
     * Display the specified product (sin autenticación)
     */
    public function show(Producto $producto): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $producto,
            'message' => 'Producto obtenido correctamente'
        ]);
    }

    /**
     * Update the specified product (sin autenticación)
     */
    public function update(Request $request, Producto $producto): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'category' => 'sometimes|required|string|max:100',
                'price' => 'sometimes|required|numeric|min:0',
                'stock' => 'sometimes|required|integer|min:0',
                'sku' => 'sometimes|required|string|unique:productos,sku,' . $producto->id,
                'active' => 'sometimes|boolean'
            ]);

            $producto->update($validated);

            return response()->json([
                'success' => true,
                'data' => $producto,
                'message' => 'Producto actualizado exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Error de validación'
            ], 422);
        }
    }

    /**
     * Remove the specified product (sin autenticación)
     */
    public function destroy(Producto $producto): JsonResponse
    {
        $producto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado exitosamente'
        ]);
    }
}
