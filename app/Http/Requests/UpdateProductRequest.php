<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'        => ['required','string','max:150'],
            'slug'        => ['nullable','string','max:180', Rule::unique('products','slug')->ignore($this->product->id)],
            'category_id' => ['required','exists:categories,id'],
            'description' => ['nullable','string'],
            'price'       => ['required','numeric','min:0'],
            'is_active'   => ['nullable','boolean'],

            'variants'            => ['nullable','array'],
            'variants.*.id'       => ['nullable','integer','exists:product_variants,id'],
            'variants.*.sku'      => ['required','string','max:64'],
            'variants.*.variant'  => ['required','string','max:120'],
            'variants.*.price'    => ['required','numeric','min:0'],
            'variants.*.stock'    => ['required','integer','min:0'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $items = collect($this->input('variants', []));
            $dupes = $items
                ->groupBy(fn($r) => strtolower(trim($r['sku'] ?? '')))
                ->filter(fn($g) => $g->count() > 1 && $g->keys()->containsStrict(null) === false)
                ->keys();

            if ($dupes->isNotEmpty()) {
                $v->errors()->add('variants', 'Terdapat SKU duplikat (case-insensitive): '.implode(', ', $dupes->all()));
            }
        });
    }

}
