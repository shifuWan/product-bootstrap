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
        $chunks = 100;     // 100 x 1,000 = 100,000
        $perChunk = 1000;

        for ($i = 0; $i < $chunks; $i++) {
            Product::factory()->count($perChunk)->create();
            
            $this->command?->info("Seeded chunk ".($i+1)."/{$chunks}");
        }
    }
}
