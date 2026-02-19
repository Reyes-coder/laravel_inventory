<?php

use App\Models\User;
use App\Models\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Validation Tests', function () {

    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    test('producto name is required', function () {
        $this->actingAs($this->user)
            ->post('/productos', [
                'price' => 100,
            ])
            ->assertSessionHasErrors('name');
    });

    test('producto price is required', function () {
        $this->actingAs($this->user)
            ->post('/productos', [
                'name' => 'Product',
            ])
            ->assertSessionHasErrors('price');
    });

    test('producto price must be numeric', function () {
        $categoria = Categoria::factory()->create();

        $this->actingAs($this->user)
            ->post('/productos', [
                'name' => 'Product',
                'price' => 'invalid',
                'categoria_id' => $categoria->id
            ])
            ->assertSessionHasErrors('price');
    });

    test('producto price must be greater than zero', function () {
        $categoria = Categoria::factory()->create();

        $this->actingAs($this->user)
            ->post('/productos', [
                'name' => 'Product',
                'price' => 0,
                'categoria_id' => $categoria->id
            ])
            ->assertSessionHasErrors('price');
    });

    test('producto stock must be numeric', function () {
        $categoria = Categoria::factory()->create();

        $this->actingAs($this->user)
            ->post('/productos', [
                'name' => 'Product',
                'price' => 50,
                'stock' => 'invalid',
                'categoria_id' => $categoria->id
            ])
            ->assertSessionHasErrors('stock');
    });

    test('producto stock must be greater than or equal to zero', function () {
        $categoria = Categoria::factory()->create();

        $this->actingAs($this->user)
            ->post('/productos', [
                'name' => 'Product',
                'price' => 50,
                'stock' => -1,
                'categoria_id' => $categoria->id
            ])
            ->assertSessionHasErrors('stock');
    });

    test('categoria name is required', function () {
        $this->actingAs($this->user)
            ->post('/categorias', [])
            ->assertSessionHasErrors('name');
    });

    test('categoria name must be string', function () {
        $this->actingAs($this->user)
            ->post('/categorias', [
                'name' => 12345
            ])
            ->assertSessionHasErrors('name');
    });

    test('categoria description must be string if provided', function () {
        $this->actingAs($this->user)
            ->post('/categorias', [
                'name' => 'Category',
                'description' => 12345
            ])
            ->assertSessionHasErrors('description');
    });

    test('producto description can be empty', function () {
        $categoria = Categoria::factory()->create();

        $response = $this->actingAs($this->user)
            ->post('/productos', [
                'name' => 'Product',
                'price' => 50,
                'description' => '',
                'categoria_id' => $categoria->id
            ]);

        $response->assertRedirect();
    });

    test('producto sku must be unique per user', function () {
        $categoria = Categoria::factory()->create();

        $this->actingAs($this->user)
            ->post('/productos', [
                'name' => 'Product 1',
                'price' => 50,
                'sku' => 'SKU-001',
                'categoria_id' => $categoria->id
            ])
            ->assertRedirect();

        $this->actingAs($this->user)
            ->post('/productos', [
                'name' => 'Product 2',
                'price' => 60,
                'sku' => 'SKU-001',
                'categoria_id' => $categoria->id
            ])
            ->assertSessionHasErrors('sku');
    });

    test('different users can use same sku', function () {
        $user2 = User::factory()->create();
        $categoria = Categoria::factory()->create();

        $this->actingAs($this->user)
            ->post('/productos', [
                'name' => 'Product 1',
                'price' => 50,
                'sku' => 'SKU-001',
                'categoria_id' => $categoria->id
            ])
            ->assertRedirect();

        $this->actingAs($user2)
            ->post('/productos', [
                'name' => 'Product 2',
                'price' => 60,
                'sku' => 'SKU-001',
                'categoria_id' => $categoria->id
            ])
            ->assertRedirect();
    });
});
