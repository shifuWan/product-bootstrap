<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'slug'           => $this->slug,
            'price'          => (float) $this->price,
            'is_active'      => (bool) $this->is_active,
            'category'       => $this->whenLoaded('category', fn() => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ]),
            'rating' => $this->reviews_avg_rating ? round((float)$this->reviews_avg_rating, 2) : null,
            'reviews_count' => $this->reviews_count ?? 0,

        ];
    }
}
