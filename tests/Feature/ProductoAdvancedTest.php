<?php

use App\Models\User;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'user']);
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->categoria = Categoria::factory()->create();
});

describe('Producto Feature Tests', function () {

    test('user can view all their products', function () {
        Producto::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'categoria_id' => $this->categoria->id
        ]);

        $this->actingAs($this->user)
            ->get('/productos')
            ->assertStatus(200);
    });

    test('user cannot access products list without authentication', function () {
        $this->get('/productos')
            ->assertRedirect('/login');
    });

    test('user can create product with valid data', function () {
        $data = [
            'name' => 'Laptop',
            'description' => 'Laptop gaming',
            'price' => 999.99,
            'stock' => 10,
            'sku' => 'LAP-001',
            'categoria_id' => $this->categoria->id,
            'active' => true
        ];

        $this->actingAs($this->user)
            ->post('/productos', $data)
            ->assertRedirect('/productos');

        $this->assertDatabaseHas('productos', [
            'name' => 'Laptop',
            'user_id' => $this->user->id
        ]);
    });

    test('user cannot create product with invalid price', function () {
        $data = [
            'name' => 'Producto',
            'price' => -100,
            'categoria_id' => $this->categoria->id
        ];

        $this->actingAs($this->user)
            ->post('/productos', $data)
            ->assertSessionHasErrors('price');
    });

    test('user cannot create product with negative stock', function () {
        $data = [
            'name' => 'Producto',
            'price' => 50,
            'stock' => -5,
            'categoria_id' => $this->categoria->id
        ];

        $this->actingAs($this->user)
            ->post('/productos', $data)
            ->assertSessionHasErrors('stock');
    });

    test('user can edit their own product', function () {
        $producto = Producto::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Original'
        ]);

        $this->actingAs($this->user)
            ->put("/productos/{$producto->id}", [
                'name' => 'Actualizado',
                'price' => 99.99,
                'categoria_id' => $this->categoria->id
            ])
            ->assertRedirect("/productos/{$producto->id}");

        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'name' => 'Actualizado'
        ]);
    });

    test('user cannot edit other users products', function () {
        $otherUser = User::factory()->create(['role' => 'user']);
        $producto = Producto::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'Original'
        ]);

        $this->actingAs($this->user)
            ->put("/productos/{$producto->id}", [
                'name' => 'Hack'
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'name' => 'Original'
        ]);
    });

    test('admin can edit any product', function () {
        $producto = Producto::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Original'
        ]);

        $this->actingAs($this->admin)
            ->put("/productos/{$producto->id}", [
                'name' => 'Admin Edit',
                'price' => 49.99,
                'categoria_id' => $this->categoria->id
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'name' => 'Admin Edit'
        ]);
    });

    test('user can delete their own product', function () {
        $producto = Producto::factory()->create([
            'user_id' => $this->user->id
        ]);

        $this->actingAs($this->user)
            ->delete("/productos/{$producto->id}")
            ->assertRedirect('/productos');

        $this->assertDatabaseMissing('productos', [
            'id' => $producto->id
        ]);
    });

    test('user cannot delete other users products', function () {
        $otherUser = User::factory()->create(['role' => 'user']);
        $producto = Producto::factory()->create([
            'user_id' => $otherUser->id
        ]);

        $this->actingAs($this->user)
            ->delete("/productos/{$producto->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('productos', [
            'id' => $producto->id
        ]);
    });

    test('user can view product details', function () {
        $producto = Producto::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Product'
        ]);

        $this->actingAs($this->user)
            ->get("/productos/{$producto->id}")
            ->assertStatus(200)
            ->assertSee('Test Product');
    });

    test('product validation requires name', function () {
        $this->actingAs($this->user)
            ->post('/productos', [
                'name' => '',
                'price' => 50,
                'categoria_id' => $this->categoria->id
            ])
            ->assertSessionHasErrors('name');
    });

    test('product validation requires categoria', function () {
        $this->actingAs($this->user)
            ->post('/productos', [
                'name' => 'Test',
                'price' => 50,
                'categoria_id' => 9999
            ])
            ->assertSessionHasErrors('categoria_id');
    });

    test('user can search products by name', function () {
        Producto::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Laptop',
            'categoria_id' => $this->categoria->id
        ]);
        Producto::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Mouse',
            'categoria_id' => $this->categoria->id
        ]);

        $this->actingAs($this->user)
            ->get('/productos?search=Laptop')
            ->assertStatus(200);
    });

    test('user can filter products by category', function () {
        $categoria2 = Categoria::factory()->create();

        Producto::factory()->create([
            'user_id' => $this->user->id,
            'categoria_id' => $this->categoria->id
        ]);
        Producto::factory()->create([
            'user_id' => $this->user->id,
            'categoria_id' => $categoria2->id
        ]);

        $this->actingAs($this->user)
            ->get("/productos?categoria={$this->categoria->id}")
            ->assertStatus(200);
    });

    test('user can filter products by active status', function () {
        Producto::factory()->create([
            'user_id' => $this->user->id,
            'active' => true,
            'categoria_id' => $this->categoria->id
        ]);
        Producto::factory()->create([
            'user_id' => $this->user->id,
            'active' => false,
            'categoria_id' => $this->categoria->id
        ]);

        $this->actingAs($this->user)
            ->get('/productos?active=1')
            ->assertStatus(200);
    });
});
