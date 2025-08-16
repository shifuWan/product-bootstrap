<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SweetAlert2\Laravel\Swal;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get(['id','name','slug']);

        return view('products.index', compact('categories'));
    }

    public function indexApi(Request $request)
    {
        $limit = min(max((int) $request->get('limit', 20), 1), 100);
        $page  = (int) $request->get('page', 1);

        $search   = $request->get('search');
        $category = $request->get('category');    
        $min      = $request->get('min_price');
        $max      = $request->get('max_price');
        $sort     = $request->get('sort', 'name'); // name|price
        $dir      = $request->get('dir', 'asc');   // asc|desc

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
        $categories = Category::orderBy('name')->get(['id','name','slug']);
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        $validated['slug'] = $this->generateSlug($validated['name']);

        Product::create($validated);

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
        $categories = Category::orderBy('name')->get(['id','name','slug']);
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();
        $validated['slug'] = $this->generateSlug($validated['name']);
        $product->update($validated);

        Swal::success([
            'title' => 'Success!',
            'text' => 'Product updated successfully',
        ]);
        return redirect()->route('products.index');
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
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }
}
