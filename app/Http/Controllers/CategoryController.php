<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Str;
use SweetAlert2\Laravel\Swal;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::paginate(10);
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->store('categories', 'public');
            $validated['image'] = Storage::url('categories/' . $image->hashName());
        }

        $category = Category::create($validated);
        Swal::success([
            'title' => 'OK',
            'text' => 'Category created successfully',
        ]);
        return redirect()->route('categories.index')->with('success', 'Category created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->store('categories', 'public');
            $validated['image'] = Storage::url('categories/' . $image->hashName());
        }

        $category->update($validated);
        Swal::success([
            'title' => 'OK',
            'text' => 'Category updated successfully',
        ]);
        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        Swal::success([
            'title' => 'OK',
            'text' => 'Category deleted successfully',
        ]);
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully');
    }
}
