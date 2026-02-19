<?php

use App\Models\User;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Authorization Tests', function () {

    test('guest user cannot access protected routes', function () {
        $this->get('/productos')
            ->assertRedirect('/login');

        $this->get('/categorias')
            ->assertRedirect('/login');
    });

    test('authenticated user can access producto routes', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/productos')
            ->assertStatus(200);
    });

    test('authenticated user can access categoria routes', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/categorias')
            ->assertStatus(200);
    });

    test('user cannot modify other users product', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $categoria = Categoria::factory()->create();

        $producto = Producto::factory()->create([
            'user_id' => $user1->id,
            'categoria_id' => $categoria->id
        ]);

        $this->actingAs($user2)
            ->put("/productos/{$producto->id}", [
                'name' => 'Hacked'
            ])
            ->assertForbidden();
    });

    test('user cannot delete other users product', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $categoria = Categoria::factory()->create();

        $producto = Producto::factory()->create([
            'user_id' => $user1->id,
            'categoria_id' => $categoria->id
        ]);

        $this->actingAs($user2)
            ->delete("/productos/{$producto->id}")
            ->assertForbidden();
    });

    test('admin user can modify any product', function () {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $categoria = Categoria::factory()->create();

        $producto = Producto::factory()->create([
            'user_id' => $user->id,
            'categoria_id' => $categoria->id,
            'name' => 'Original'
        ]);

        $this->actingAs($admin)
            ->put("/productos/{$producto->id}", [
                'name' => 'Modified by Admin',
                'categoria_id' => $categoria->id
            ])
            ->assertSuccessful();
    });

    test('admin user can delete any product', function () {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $categoria = Categoria::factory()->create();

        $producto = Producto::factory()->create([
            'user_id' => $user->id,
            'categoria_id' => $categoria->id
        ]);

        $this->actingAs($admin)
            ->delete("/productos/{$producto->id}")
            ->assertSuccessful();
    });

    test('user role cannot access admin routes', function () {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get('/admin/dashboard')
            ->assertForbidden();
    });

    test('admin role can access admin routes', function () {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertStatus(200);
    });
});
