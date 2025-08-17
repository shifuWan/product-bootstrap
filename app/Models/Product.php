<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes, HasUlids;

    protected $fillable = [
        'category_id','name','slug','description','price','is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }   

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function scopeSearch($q, ?string $term)
    {
        return $q->where('name', 'LIKE', '%'.$term.'%');
    }

    public function scopeCategorySlug($q, ?string $slug)
    {
        if (!$slug) return $q;
        return $q->whereHas('category', fn($c) => $c->where('slug', $slug));
    }

    public function scopePriceBetween($q, $min, $max)
    {
        return $q
            ->when($min !== null, fn($qq) => $qq->where('price', '>=', (float)$min))
            ->when($max !== null, fn($qq) => $qq->where('price', '<=', (float)$max));
    }

    public function scopeSortBy($q, ?string $field, ?string $direction)
    {
        $allowed = ['price', 'name'];
        $field = in_array($field, $allowed, true) ? $field : 'name';
        $direction = $direction === 'desc' ? 'desc' : 'asc';
        return $q->orderBy($field, $direction);
    }
}
