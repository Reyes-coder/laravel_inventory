<?php

use App\Models\User;
use App\Models\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'user']);
});

test('user can view categories', function () {
    Categoria::factory()->count(3)->create();

    $this->actingAs($this->user)
        ->get('/categorias')
        ->assertStatus(200);
});

test('user can create a category', function () {
    $data = [
        'name' => 'Electrónica',
        'description' => 'Productos electrónicos'
    ];

    $this->actingAs($this->user)
        ->post('/categorias', $data)
        ->assertRedirect('/categorias');

    $this->assertDatabaseHas('categorias', [
        'name' => 'Electrónica',
        'slug' => 'electronica'
    ]);
});

test('category name must be unique', function () {
    Categoria::create([
        'name' => 'Electrónica',
        'slug' => 'electronica'
    ]);

    $this->actingAs($this->user)
        ->post('/categorias', [
            'name' => 'Electrónica',
            'description' => 'Duplicado'
        ])
        ->assertSessionHasErrors('name');
});

test('user can update a category', function () {
    $categoria = Categoria::factory()->create();

    $this->actingAs($this->user)
        ->patch("/categorias/{$categoria->id}", [
            'name' => 'Electrónica Actualizada',
            'description' => 'Nueva descripción'
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('categorias', [
        'id' => $categoria->id,
        'name' => 'Electrónica Actualizada'
    ]);
});

test('user can delete a category', function () {
    $categoria = Categoria::factory()->create();

    $this->actingAs($this->user)
        ->delete("/categorias/{$categoria->id}")
        ->assertRedirect('/categorias');

    $this->assertDatabaseMissing('categorias', ['id' => $categoria->id]);
});

test('category can have many products', function () {
    $categoria = Categoria::factory()->create();

    $this->actingAs($this->user)
        ->get("/categorias/{$categoria->id}")
        ->assertStatus(200);
});

