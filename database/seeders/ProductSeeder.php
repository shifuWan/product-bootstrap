<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chunks = 20;     // 20 x 1,000 = 20,000
        $perChunk = 100;

        $start = microtime(true);
        for ($i = 0; $i < $chunks; $i++) {
            $products = Product::factory()->count($perChunk)->create();
            $products->each(function ($p) {
                ProductVariant::factory(fake()->numberBetween(2, 5))
                    ->for($p)
                    ->create();

                if (fake()->boolean(70)) {
                    Review::factory(fake()->numberBetween(1, 5))
                        ->for($p)
                        ->create();
                }
            });

            $this->command->info("Seeded chunk " . ($i + 1) . "/{$chunks}");
        }
        $end = microtime(true);
        $this->command->info("Time taken: " . round($end - $start, 2) . " seconds");
    }
}
