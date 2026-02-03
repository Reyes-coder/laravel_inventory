<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;
use Illuminate\Support\Str;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['name' => 'Electrónica', 'description' => 'Productos electrónicos y accesorios'],
            ['name' => 'Ropa y Accesorios', 'description' => 'Prendas de vestir y complementos'],
            ['name' => 'Hogar y Cocina', 'description' => 'Artículos para el hogar y cocina'],
            ['name' => 'Deportes', 'description' => 'Equipos y accesorios deportivos'],
            ['name' => 'Libros', 'description' => 'Libros y material de lectura'],
            ['name' => 'Juguetes', 'description' => 'Juguetes y juegos'],
            ['name' => 'Belleza y Cuidado', 'description' => 'Productos de belleza y cuidado personal'],
            ['name' => 'Alimentos y Bebidas', 'description' => 'Productos alimenticios y bebidas'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create([
                'name' => $categoria['name'],
                'description' => $categoria['description'],
                'slug' => Str::slug($categoria['name'])
            ]);
        }
    }
}

