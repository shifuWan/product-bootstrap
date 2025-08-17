<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;


class ProductSeeder extends Seeder
{
    // ukuran dataset
    private int $TOTAL_PRODUCTS = 100_000;

    private int $BATCH_SIZE = 2_000;

    private int $SUB_BATCH = 5_000;

    public function run(): void
    {
        DB::disableQueryLog();
        $oldDispatcher = \Illuminate\Database\Eloquent\Model::getEventDispatcher();
        \Illuminate\Database\Eloquent\Model::unsetEventDispatcher();


        $categoryIds = Category::pluck('id')->all();
        $userIds     = User::pluck('id')->all();

        $adjs  = ['Ultra','Pro','Prime','Max','Lite','Neo','Air','Flex','Core','Edge','Nova','Mega'];
        $nouns = ['Laptop','Phone','Watch','Shirt','Shoes','Bag','Headset','Camera','Printer','Tablet','Router','Keyboard'];

        $batches = (int) ceil($this->TOTAL_PRODUCTS / $this->BATCH_SIZE);
        $globalSkuCounter = 1;

        for ($b = 0; $b < $batches; $b++) {
            $toMake = min($this->BATCH_SIZE, $this->TOTAL_PRODUCTS - $b * $this->BATCH_SIZE);

            $products = [];
            $slugs    = [];
            $now = now();

            for ($i = 0; $i < $toMake; $i++) {
                $name = $adjs[array_rand($adjs)] . ' ' .
                        $nouns[array_rand($nouns)] . ' ' .
                        Str::upper(Str::random(3)) . '-' . ($b*$this->BATCH_SIZE + $i + 1);

                $slug = Str::slug($name);
                $slugs[] = $slug;

                $products[] = [
                    'id' => Str::ulid(),
                    'category_id' => $categoryIds[array_rand($categoryIds)],
                    'name'        => $name,
                    'slug'        => $slug,
                    'description' => null,
                    'price'       => mt_rand(500, 500000) / 100, // 5.00 - 5000.00
                    'is_active'   => true,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }

            // Insert produk (bulk)
            DB::table('products')->insert($products);

            $idBySlug = Product::whereIn('slug', $slugs)->pluck('id', 'slug');

            $variantRows = [];
            $reviewRows  = [];

            foreach ($slugs as $slug) {
                $productId = $idBySlug[$slug] ?? null;
                if (!$productId) continue;

                $vCount = random_int(2, 5);
                for ($k = 0; $k < $vCount; $k++) {
                    $sku = 'SKU-' . strtoupper(base_convert($globalSkuCounter++, 10, 36));
                    $color = ['Black','White','Red','Blue','Green','Gray'][array_rand(['Black','White','Red','Blue','Green','Gray'])];
                    $size  = ['S','M','L','XL'][array_rand(['S','M','L','XL'])];

                    $variantRows[] = [
                        'id' => Str::ulid(),
                        'product_id' => $productId,
                        'sku'        => $sku,
                        'variant'    => "Color: {$color} / Size: {$size}",
                        'price'      => mt_rand(500, 500000) / 100,
                        'stock'      => random_int(0, 500),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if (random_int(1, 100) <= 70) {
                    $rCount = random_int(1, 5);
                    for ($r = 0; $r < $rCount; $r++) {
                        $reviewRows[] = [
                            'id' => Str::ulid(),
                            'product_id' => $productId,
                            'rating'     => random_int(1, 5),
                            'comment'    => null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }

                if (count($variantRows) >= $this->SUB_BATCH) {
                    DB::table('product_variants')->insert($variantRows);
                    $variantRows = [];
                }
                if (count($reviewRows) >= $this->SUB_BATCH) {
                    DB::table('reviews')->insert($reviewRows);
                    $reviewRows = [];
                }
            }

            if (!empty($variantRows)) {
                DB::table('product_variants')->insert($variantRows);
            }
            if (!empty($reviewRows)) {
                DB::table('reviews')->insert($reviewRows);
            }

            $this->command?->info("Batch ".($b+1)."/{$batches} selesai: {$toMake} products");
        }

        \Illuminate\Database\Eloquent\Model::setEventDispatcher($oldDispatcher);
    }
}
