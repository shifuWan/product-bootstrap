<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = ucfirst(fake()->unique()->words(3, true));
        return [
            'name' => $name,
            'category_id' => Category::inRandomOrder()->value('id') ?? Category::factory(),
            'slug' => Str::slug($name . '-' . fake()->unique()->numerify('###')),
            'description' => fake()->optional()->paragraphs(2, true),
            'price' => fake()->randomFloat(2, 5, 5000),
            'is_active' => fake()->randomElement([true, false]),
        ];
    }
}
