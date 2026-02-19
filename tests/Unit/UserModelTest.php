<?php

use App\Models\User;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('User Model', function () {

    test('user can be created with factory', function () {
        $user = User::factory()->create();

        expect($user->id)->toBeGreaterThan(0);
        expect($user->email)->toBeTruthy();
        expect($user->name)->toBeTruthy();
    });

    test('user has many productos', function () {
        $user = User::factory()->create();
        $productos = Producto::factory()->count(5)->create(['user_id' => $user->id]);

        $user->refresh();
        expect($user->productos)->toHaveCount(5);
        expect($user->productos->first())->toBeInstanceOf(Producto::class);
    });

    test('user can have admin role', function () {
        $admin = User::factory()->create(['role' => 'admin']);

        expect($admin->role)->toBe('admin');
    });

    test('user can have user role', function () {
        $user = User::factory()->create(['role' => 'user']);

        expect($user->role)->toBe('user');
    });

    test('user email is unique', function () {
        User::factory()->create(['email' => 'test@example.com']);

        expect(User::where('email', 'test@example.com')->count())->toBe(1);
    });

    test('user password is hashed', function () {
        $user = User::factory()->create();

        expect($user->password)->not->toBe('password');
    });

    test('user can update their information', function () {
        $user = User::factory()->create(['name' => 'Original']);
        $user->update(['name' => 'Actualizado']);

        expect($user->refresh()->name)->toBe('Actualizado');
    });

    test('user can be deleted', function () {
        $user = User::factory()->create();
        $id = $user->id;

        $user->delete();

        expect(User::find($id))->toBeNull();
    });

    test('user has timestamps', function () {
        $user = User::factory()->create();

        expect($user->created_at)->toBeTruthy();
        expect($user->updated_at)->toBeTruthy();
    });
});
