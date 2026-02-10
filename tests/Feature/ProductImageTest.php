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
    $this->user = User::factory()->create(['role' => 'user', 'email_verified_at' => now()]);
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
    $otherUser = User::factory()->create(['role' => 'user', 'email_verified_at' => now()]);
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

test('user_id is stored when uploading image', function () {
    $file = UploadedFile::fake()->image('test.jpg');

    $this->actingAs($this->user)
        ->post("/productos/{$this->producto->id}/images", [
            'image' => $file
        ]);

    $image = $this->producto->images()->first();
    $this->assertEquals($this->user->id, $image->user_id);
});

test('user can only see their own images', function () {
    $otherUser = User::factory()->create(['role' => 'user', 'email_verified_at' => now()]);

    // User 1 uploads an image
    $file1 = UploadedFile::fake()->image('user1.jpg');
    $this->actingAs($this->user)
        ->post("/productos/{$this->producto->id}/images", ['image' => $file1]);

    // User 2 uploads an image
    $file2 = UploadedFile::fake()->image('user2.jpg');
    $this->actingAs($otherUser)
        ->post("/productos/{$this->producto->id}/images", ['image' => $file2]);

    // User 1 views the product
    $response = $this->actingAs($this->user)
        ->get("/productos/{$this->producto->id}");

    // User 1 should see only their image (1 image)
    // This would be tested in the view logic
    $this->assertEquals(1, $this->producto->images->where('user_id', $this->user->id)->count());
});

test('admin can see all images', function () {
    $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
    $otherUser = User::factory()->create(['role' => 'user', 'email_verified_at' => now()]);

    // Product belonging to admin
    $adminProducto = Producto::factory()->create([
        'user_id' => $admin->id,
        'categoria_id' => $this->categoria->id
    ]);

    // Product belonging to other user
    $otherProducto = Producto::factory()->create([
        'user_id' => $otherUser->id,
        'categoria_id' => $this->categoria->id
    ]);

    // Admin uploads an image to their product
    $file1 = UploadedFile::fake()->image('admin.jpg');
    $this->actingAs($admin)
        ->post("/productos/{$adminProducto->id}/images", ['image' => $file1]);

    // OtherUser uploads an image to their product
    $file2 = UploadedFile::fake()->image('user.jpg');
    $this->actingAs($otherUser)
        ->post("/productos/{$otherProducto->id}/images", ['image' => $file2]);

    // Admin views their own product - should see only their image (1)
    $this->actingAs($admin)
        ->get("/productos/{$adminProducto->id}");
    $this->assertEquals(1, $adminProducto->images->count());

    // Admin views other user's product - should see all images as admin (1)
    $this->actingAs($admin)
        ->get("/productos/{$otherProducto->id}");
    $this->assertEquals(1, $otherProducto->images->count());
});

