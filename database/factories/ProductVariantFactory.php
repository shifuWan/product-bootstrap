<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $color = fake()->randomElement(['Black','White','Red','Blue','Green','Gray']);
        $size  = fake()->randomElement(['S','M','L','XL']);
        return [
            'sku'     => strtoupper(fake()->unique()->bothify('SKU-########')),
            'variant' => "Color: {$color} / Size: {$size}",
            'price'   => fake()->randomFloat(2, 5, 5000),
            'stock'   => fake()->numberBetween(0, 500),
        ];
    }
}
