<?php

use App\Models\User;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'user']);
    $this->admin = User::factory()->create(['role' => 'admin']);
});

describe('Categoria Feature Tests', function () {

    test('user can view all categories', function () {
        Categoria::factory()->count(5)->create();

        $this->actingAs($this->user)
            ->get('/categorias')
            ->assertStatus(200);
    });

    test('user cannot access categories without authentication', function () {
        $this->get('/categorias')
            ->assertRedirect('/login');
    });

    test('user can create category with valid data', function () {
        $data = [
            'name' => 'Electrónica',
            'description' => 'Productos electrónicos'
        ];

        $this->actingAs($this->user)
            ->post('/categorias', $data)
            ->assertRedirect('/categorias');

        $this->assertDatabaseHas('categorias', [
            'name' => 'Electrónica'
        ]);
    });

    test('category name is required', function () {
        $this->actingAs($this->user)
            ->post('/categorias', [
                'description' => 'Sin nombre'
            ])
            ->assertSessionHasErrors('name');
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

    test('user can view category details', function () {
        $categoria = Categoria::factory()->create(['name' => 'Test Category']);

        $this->actingAs($this->user)
            ->get("/categorias/{$categoria->id}")
            ->assertStatus(200)
            ->assertSee('Test Category');
    });

    test('user can edit category', function () {
        $categoria = Categoria::factory()->create(['name' => 'Original']);

        $this->actingAs($this->user)
            ->put("/categorias/{$categoria->id}", [
                'name' => 'Actualizada',
                'description' => 'Nueva descripción'
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('categorias', [
            'id' => $categoria->id,
            'name' => 'Actualizada'
        ]);
    });

    test('user can delete category', function () {
        $categoria = Categoria::factory()->create();

        $this->actingAs($this->user)
            ->delete("/categorias/{$categoria->id}")
            ->assertRedirect('/categorias');

        $this->assertDatabaseMissing('categorias', [
            'id' => $categoria->id
        ]);
    });

    test('category cannot be deleted if has productos', function () {
        $categoria = Categoria::factory()->create();
        Producto::factory()->create(['categoria_id' => $categoria->id]);

        $this->actingAs($this->user)
            ->delete("/categorias/{$categoria->id}")
            ->assertSessionHasErrors();

        $this->assertDatabaseHas('categorias', [
            'id' => $categoria->id
        ]);
    });

    test('category slug is unique', function () {
        Categoria::create([
            'name' => 'Electrónica',
            'slug' => 'electronica'
        ]);

        $this->actingAs($this->user)
            ->post('/categorias', [
                'name' => 'Electrónica 2',
                'slug' => 'electronica'
            ])
            ->assertSessionHasErrors('slug');
    });

    test('category description is optional', function () {
        $data = [
            'name' => 'Nueva Categoría'
        ];

        $this->actingAs($this->user)
            ->post('/categorias', $data)
            ->assertRedirect('/categorias');

        $this->assertDatabaseHas('categorias', [
            'name' => 'Nueva Categoría'
        ]);
    });

    test('user can list products within a category', function () {
        $categoria = Categoria::factory()->create();
        Producto::factory()->count(3)->create(['categoria_id' => $categoria->id]);

        $this->actingAs($this->user)
            ->get("/categorias/{$categoria->id}/productos")
            ->assertStatus(200);
    });

    test('category name cannot be too long', function () {
        $longName = str_repeat('a', 256);

        $this->actingAs($this->user)
            ->post('/categorias', [
                'name' => $longName
            ])
            ->assertSessionHasErrors('name');
    });

    test('admin can manage all categories', function () {
        $categoria = Categoria::factory()->create(['name' => 'Original']);

        $this->actingAs($this->admin)
            ->put("/categorias/{$categoria->id}", [
                'name' => 'Admin Update'
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('categorias', [
            'id' => $categoria->id,
            'name' => 'Admin Update'
        ]);
    });

    test('category count is correct', function () {
        Categoria::factory()->count(10)->create();

        $this->actingAs($this->user)
            ->get('/categorias')
            ->assertStatus(200);

        expect(Categoria::count())->toBe(10);
    });
});
