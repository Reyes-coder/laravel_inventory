<?php

use App\Models\User;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    $this->user = User::factory()->create(['role' => 'user']);
    $this->categoria = Categoria::factory()->create();
    $this->producto = Producto::factory()->create([
        'user_id' => $this->user->id,
        'categoria_id' => $this->categoria->id
    ]);
});

test('user can upload an image to their product', function () {
    $file = UploadedFile::fake()->image('test.jpg');

    $this->actingAs($this->user)
        ->post("/productos/{$this->producto->id}/images", [
            'image' => $file
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('product_images', [
        'producto_id' => $this->producto->id
    ]);

    Storage::disk('public')->assertExists($this->producto->images()->first()->path);
});

test('first image is automatically set as primary', function () {
    $file = UploadedFile::fake()->image('test.jpg');

    $this->actingAs($this->user)
        ->post("/productos/{$this->producto->id}/images", [
            'image' => $file
        ]);

    $image = $this->producto->images()->first();
    $this->assertTrue($image->is_primary);
});

test('second image is not set as primary', function () {
    $file1 = UploadedFile::fake()->image('test1.jpg');
    $file2 = UploadedFile::fake()->image('test2.jpg');

    $this->actingAs($this->user)
        ->post("/productos/{$this->producto->id}/images", ['image' => $file1]);

    $this->actingAs($this->user)
        ->post("/productos/{$this->producto->id}/images", ['image' => $file2]);

    $images = $this->producto->images()->get();
    $this->assertTrue($images[0]->is_primary);
    $this->assertFalse($images[1]->is_primary);
});

test('user can set an image as primary', function () {
    $image1 = ProductImage::factory()->create([
        'producto_id' => $this->producto->id,
        'is_primary' => true
    ]);

    $image2 = ProductImage::factory()->create([
        'producto_id' => $this->producto->id,
        'is_primary' => false
    ]);

    $this->actingAs($this->user)
        ->patch("/product-images/{$image2->id}/set-primary")
        ->assertRedirect();

    $image1->refresh();
    $image2->refresh();

    $this->assertFalse($image1->is_primary);
    $this->assertTrue($image2->is_primary);
});

test('user can delete an image from their product', function () {
    $image = ProductImage::factory()->create([
        'producto_id' => $this->producto->id,
        'is_primary' => true
    ]);

    $path = $image->path;
    Storage::disk('public')->put($path, 'test');

    $this->actingAs($this->user)
        ->delete("/product-images/{$image->id}")
        ->assertRedirect();

    $this->assertDatabaseMissing('product_images', ['id' => $image->id]);
    Storage::disk('public')->assertMissing($path);
});

test('user cannot upload image to other users product', function () {
    $otherUser = User::factory()->create();
    $otherProducto = Producto::factory()->create([
        'user_id' => $otherUser->id,
        'categoria_id' => $this->categoria->id
    ]);

    $file = UploadedFile::fake()->image('test.jpg');

    $this->actingAs($this->user)
        ->post("/productos/{$otherProducto->id}/images", ['image' => $file])
        ->assertStatus(403);
});

test('image validation rejects non-image files', function () {
    $file = UploadedFile::fake()->create('document.pdf');

    $this->actingAs($this->user)
        ->post("/productos/{$this->producto->id}/images", [
            'image' => $file
        ])
        ->assertSessionHasErrors('image');
});

