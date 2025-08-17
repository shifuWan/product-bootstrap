<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SweetAlert2\Laravel\Swal;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get(['id', 'name', 'slug']);

        return view('products.index', compact('categories'));
    }

    public function indexApi(Request $request)
    {
        $limit = min(max((int)$request->get('limit', 20), 1), 100);
        $page = (int)$request->get('page', 1);

        $search = $request->get('search');
        $category = $request->get('category');
        $min = $request->get('min_price');
        $max = $request->get('max_price');
        $sort = $request->get('sort', 'name'); // name|price
        $dir = $request->get('dir', 'asc');   // asc|desc

        $products = Product::with('category')
            ->search($search)
            ->categorySlug($category)
            ->priceBetween($min, $max)
            ->sortBy($sort, $dir)
            ->paginate($limit)->withQueryString();


        return ProductResource::collection($products);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get(['id', 'name', 'slug']);
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        $validated['is_active'] = (bool)$request->boolean('is_active');
        $validated['slug'] = $this->generateSlug($validated['name']);


        DB::transaction(function () use ($validated) {
            $product = Product::create([
                'id' => Str::ulid(),
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'is_active' => $validated['is_active'],
            ]);

            if (!empty($validated['variants'])) {
                $rows = array_map(function ($v) use ($product) {
                    return [
                        'id' => Str::ulid(),
                        'product_id' => $product->id,
                        'sku' => $v['sku'],
                        'variant' => $v['variant'],
                        'price' => $v['price'],
                        'stock' => $v['stock'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }, $validated['variants']);

                ProductVariant::insert($rows);
            }
        });


        Swal::success([
            'title' => 'Success!',
            'text' => 'Product created successfully',
        ]);
        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load('variants', 'category');
        $categories = Category::orderBy('name')->get(['id', 'name', 'slug']);

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['slug'] = $this->generateSlug($data['name']);

        DB::transaction(function () use ($data, $product) {
            $product->update([
                'category_id' => $data['category_id'],
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'is_active' => (bool)($data['is_active'] ?? false),
            ]);

            $incoming = collect($data['variants'] ?? []);

            $incomingIds = $incoming->pluck('id')->filter()->values();
            $existingIds = $product->variants()->pluck('id');
            $toDelete = $existingIds->diff($incomingIds);
            if ($toDelete->isNotEmpty()) {
                ProductVariant::where('product_id', $product->id)
                    ->whereIn('id', $toDelete)->delete();
            }

            foreach ($incoming as $row) {
                $payload = [
                    'sku' => $row['sku'],
                    'variant' => $row['variant'],
                    'price' => $row['price'],
                    'stock' => $row['stock'],
                ];

                if (!empty($row['id'])) {
                    $pv = ProductVariant::where('product_id', $product->id)
                        ->where('id', $row['id'])
                        ->firstOrFail();
                    $pv->update($payload);
                } else {
                    $product->variants()->create($payload);
                }
            }
        });

        Swal::success([
            'title' => 'Success!',
            'text' => 'Product updated successfully',
        ]);
        return redirect()->route('products.index')->with('status', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        Swal::success([
            'title' => 'Success!',
            'text' => 'Product deleted successfully',
        ]);
        return redirect()->route('products.index');
    }

    private function generateSlug(string $name)
    {
        return Str::slug($name . '-' . Str::random(10));
    }
}
