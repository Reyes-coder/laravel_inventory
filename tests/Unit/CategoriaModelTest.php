<?php

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Categoria Model', function () {

    test('categoria has correct fillable properties', function () {
        $categoria = new Categoria();
        $expected = ['name', 'description', 'slug'];
        expect($categoria->getFillable())->toBe($expected);
    });

    test('categoria can be created with factory', function () {
        $categoria = Categoria::factory()->create();

        expect($categoria->id)->toBeGreaterThan(0);
        expect($categoria->name)->toBeTruthy();
        expect($categoria->slug)->toBeTruthy();
    });

    test('categoria has many productos', function () {
        $categoria = Categoria::factory()->create();
        $productos = Producto::factory()->count(3)->create(['categoria_id' => $categoria->id]);

        $categoria->refresh();
        expect($categoria->productos)->toHaveCount(3);
        expect($categoria->productos->first())->toBeInstanceOf(Producto::class);
    });

    test('categoria slug is generated from name', function () {
        $categoria = Categoria::factory()->create(['name' => 'Electrónica']);

        expect($categoria->slug)->toBe('electronica');
    });

    test('categoria name is required', function () {
        $categoria = new Categoria(['description' => 'Test']);

        expect($categoria->name)->toBeNull();
    });

    test('categoria can be updated', function () {
        $categoria = Categoria::factory()->create(['name' => 'Original']);
        $categoria->update(['name' => 'Actualizada']);

        expect($categoria->refresh()->name)->toBe('Actualizada');
    });

    test('categoria can be deleted', function () {
        $categoria = Categoria::factory()->create();
        $id = $categoria->id;

        $categoria->delete();

        expect(Categoria::find($id))->toBeNull();
    });

    test('deleting categoria cascades to productos', function () {
        $categoria = Categoria::factory()->create();
        Producto::factory()->count(2)->create(['categoria_id' => $categoria->id]);

        $categoria->delete();

        expect(Producto::where('categoria_id', $categoria->id)->count())->toBe(0);
    });

    test('categoria timestamps work correctly', function () {
        $categoria = Categoria::factory()->create();

        expect($categoria->created_at)->toBeTruthy();
        expect($categoria->updated_at)->toBeTruthy();
    });
});
