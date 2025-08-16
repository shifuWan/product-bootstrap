<?php

namespace Database\Seeders;

use App\Models\Product;
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
        $perChunk = 1000;

        $start = microtime(true);
        for ($i = 0; $i < $chunks; $i++) {
            $products = Product::factory()->count($perChunk)->make();
            Product::insert($products->toArray());

            $this->command->info("Seeded chunk ".($i+1)."/{$chunks}");
        }
        $end = microtime(true);
        $this->command->info("Time taken: " . round($end - $start, 2) . " seconds");
    }
}
