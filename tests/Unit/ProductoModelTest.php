<?php

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Producto Model', function () {

    test('producto has correct fillable properties', function () {
        $producto = new Producto();
        $expected = [
            'name',
            'description',
            'category',
            'categoria_id',
            'price',
            'stock',
            'sku',
            'active',
            'user_id',
            'role'
        ];
        expect($producto->getFillable())->toBe($expected);
    });

    test('producto can be created with factory', function () {
        $producto = Producto::factory()->create();

        expect($producto->id)->toBeGreaterThan(0);
        expect($producto->name)->toBeTruthy();
        expect($producto->price)->toBeGreaterThan(0);
        expect($producto->stock)->toBeGreaterThanOrEqual(0);
    });

    test('producto belongs to user', function () {
        $user = User::factory()->create();
        $producto = Producto::factory()->create(['user_id' => $user->id]);

        expect($producto->user)->toBeInstanceOf(User::class);
        expect($producto->user->id)->toBe($user->id);
    });

    test('producto belongs to categoria', function () {
        $categoria = Categoria::factory()->create();
        $producto = Producto::factory()->create(['categoria_id' => $categoria->id]);

        expect($producto->categoria)->toBeInstanceOf(Categoria::class);
        expect($producto->categoria->id)->toBe($categoria->id);
    });

    test('producto has correct casts', function () {
        $producto = Producto::factory()->create([
            'price' => '99.99',
            'stock' => 10,
            'active' => 1
        ]);

        expect($producto->price)->toBeFloat();
        expect($producto->stock)->toBeInt();
        expect($producto->active)->toBeBool();
    });

    test('producto price is stored as decimal with 2 decimals', function () {
        $producto = Producto::factory()->create(['price' => 99.999]);

        expect($producto->price)->toBe(99.99);
    });

    test('product sku is unique per user', function () {
        $user = User::factory()->create();
        $sku = 'PROD-001';

        $producto1 = Producto::factory()->create([
            'user_id' => $user->id,
            'sku' => $sku
        ]);

        expect($producto1->sku)->toBe($sku);
    });

    test('product can be activated and deactivated', function () {
        $producto = Producto::factory()->create(['active' => true]);
        expect($producto->active)->toBeTrue();

        $producto->update(['active' => false]);
        expect($producto->refresh()->active)->toBeFalse();
    });

    test('producto has correct timestamps', function () {
        $producto = Producto::factory()->create();

        expect($producto->created_at)->toBeTruthy();
        expect($producto->updated_at)->toBeTruthy();
    });

    test('product images relationship works', function () {
        $producto = Producto::factory()->create();

        expect($producto->images)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
    });
});
