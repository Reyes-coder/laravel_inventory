<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'producto_id' => \App\Models\Producto::factory(),
            'path' => 'productos/' . $this->faker->uuid . '/' . $this->faker->image(),
            'original_name' => $this->faker->image(),
            'is_primary' => false
        ];
    }
}
