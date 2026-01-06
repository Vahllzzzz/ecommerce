<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|max:1024',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
            ->store('categories', 'public');
        }
        $validated['slug'] = Str::slug($validated['name']);
        Category::create($validated);
        return back()->with('success', 'Kategory Berhasil Ditambahkan.');
        
        Cache::forget('global_categories');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category')); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|max:1024',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')
                ->store('categories', 'public');
        }

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return back()->with('success', 'Kategori berhasil diperbarui!');
        
        Cache::forget('global_categories');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error',
                'Kategori tidak dapat dihapus karena masih memiliki produk. Silahkan pindahkan atau hapus produk terlebih dahulu.');
        }
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
        
        Cache::forget('global_categories');
    }
}
