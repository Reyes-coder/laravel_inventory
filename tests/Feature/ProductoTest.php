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

test('user can view their own products', function () {
    Producto::factory()->create([
        'user_id' => $this->user->id,
        'categoria_id' => $this->categoria->id
    ]);

    $this->actingAs($this->user)
        ->get('/productos')
        ->assertStatus(200)
        ->assertSee('Productos');
});

test('user cannot view other users products', function () {
    $otherUser = User::factory()->create(['role' => 'user']);
    Producto::factory()->create([
        'user_id' => $otherUser->id,
        'categoria_id' => $this->categoria->id
    ]);

    $response = $this->actingAs($this->user)
        ->get('/productos');

    $response->assertStatus(200);
});

test('admin can view all products', function () {
    Producto::factory()->count(3)->create(['categoria_id' => $this->categoria->id]);

    $this->actingAs($this->admin)
        ->get('/productos')
        ->assertStatus(200);
});

test('user can create a product', function () {
    $data = [
        'name' => 'Nuevo Producto',
        'description' => 'Descripción',
        'categoria_id' => $this->categoria->id,
        'price' => 99.99,
        'stock' => 10,
        'sku' => 'SKU123'
    ];

    $this->actingAs($this->user)
        ->post('/productos', $data)
        ->assertRedirect('/productos');

    $this->assertDatabaseHas('productos', [
        'name' => 'Nuevo Producto',
        'user_id' => $this->user->id
    ]);
});

test('user can update their own product', function () {
    $producto = Producto::factory()->create([
        'user_id' => $this->user->id,
        'categoria_id' => $this->categoria->id
    ]);

    $this->actingAs($this->user)
        ->patch("/productos/{$producto->id}", [
            'name' => 'Actualizado',
            'description' => 'Nueva descripción',
            'categoria_id' => $this->categoria->id,
            'price' => 49.99,
            'stock' => 5
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('productos', [
        'id' => $producto->id,
        'name' => 'Actualizado'
    ]);
});

test('user cannot delete other users product', function () {
    $producto = Producto::factory()->create([
        'user_id' => $this->admin->id,
        'categoria_id' => $this->categoria->id
    ]);

    $this->actingAs($this->user)
        ->delete("/productos/{$producto->id}")
        ->assertStatus(403);
});

test('user can delete their own product', function () {
    $producto = Producto::factory()->create([
        'user_id' => $this->user->id,
        'categoria_id' => $this->categoria->id
    ]);

    $this->actingAs($this->user)
        ->delete("/productos/{$producto->id}")
        ->assertRedirect('/productos');

    $this->assertDatabaseMissing('productos', ['id' => $producto->id]);
});

test('user can search products', function () {
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

