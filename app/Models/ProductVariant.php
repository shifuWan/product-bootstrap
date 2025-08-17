<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    /** @use HasFactory<\Database\Factories\ProductVariantFactory> */
    use HasFactory, HasUlids;

    protected $fillable = ['product_id','sku','variant','price','stock'];
    protected $casts = ['price' => 'decimal:2'];

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }
}
