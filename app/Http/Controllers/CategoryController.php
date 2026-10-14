<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori ini sudah terdaftar.',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', "Kategori '{$validated['name']}' berhasil ditambahkan.");
    }

    /**
     * Display the specified category with its products.
     */
    public function show(Category $category)
    {
        $category->load(['products' => function ($q) {
            $q->orderBy('name');
        }]);

        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the category.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the category.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($category->id)],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori ini sudah terdaftar.',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', "Kategori '{$category->name}' berhasil diperbarui.");
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', "Kategori '{$category->name}' tidak dapat dihapus karena masih menaungi {$category->products()->count()} produk pakaian aktif.");
        }

        $name = $category->name;
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', "Kategori '{$name}' berhasil dihapus.");
    }
}
