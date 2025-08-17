<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'id' => Str::ulid(),
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Electronics category',
                'image' => 'https://placehold.co/600x400.png?text=electronics',
                'is_active' => true,
            ],
            [
                'id' => Str::ulid(),
                'name' => 'Clothing',
                'slug' => 'clothing',
                'description' => 'Clothing category',
                'image' => 'https://placehold.co/600x400.png?text=clothing',
                'is_active' => true,
            ],
            [
                'id' => Str::ulid(),
                'name' => 'Books',
                'slug' => 'books',
                'description' => 'Books category',
                'image' => 'https://placehold.co/600x400.png?text=books',
                'is_active' => true,
            ],
            [
                'id' => Str::ulid(),
                'name' => 'Furniture',
                'slug' => 'furniture',
                'description' => 'Furniture category',
                'image' => 'https://placehold.co/600x400.png?text=furniture',
                'is_active' => true,
            ],
            [
                'id' => Str::ulid(),
                'name' => 'Toys',
                'slug' => 'toys',
                'description' => 'Toys category',
                'image' => 'https://placehold.co/600x400.png?text=toys',
                'is_active' => true,
            ],
            [
                'id' => Str::ulid(),
                'name' => 'Shoes',
                'slug' => 'shoes',
                'description' => 'Shoes category',
                'image' => 'https://placehold.co/600x400.png?text=shoes',
                'is_active' => true,
            ],
            [
                'id' => Str::ulid(),
                'name' => 'Jewelry',
                'slug' => 'jewelry',
                'description' => 'Jewelry category',
                'image' => 'https://placehold.co/600x400.png?text=jewelry',
                'is_active' => true,
            ],
            [
                'id' => Str::ulid(),
                'name' => 'Sports',
                'slug' => 'sports',
                'description' => 'Sports category',
                'image' => 'https://placehold.co/600x400.png?text=sports',
                'is_active' => true,
            ],
            [
                'id' => Str::ulid(),
                'name' => 'Automotive',
                'slug' => 'automotive',
                'description' => 'Automotive category',
                'image' => 'https://placehold.co/600x400.png?text=automotive',
                'is_active' => true,
            ],
        ];
        Category::insert($categories);
    }
}
