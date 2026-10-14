<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * Features: Search, Filter, Sort, Pagination (Slide 32, 38, 41)
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'suppliers']);

        // 1. Search keyword
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 2. Filter by Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 3. Filter by Stock Status
        if ($request->filled('status')) {
            if ($request->status === 'low_stock') {
                $query->whereColumn('stock', '<=', 'minimum_stock')->where('stock', '>', 0);
            } elseif ($request->status === 'out_of_stock') {
                $query->where('stock', '<=', 0);
            } elseif ($request->status === 'in_stock') {
                $query->whereColumn('stock', '>', 'minimum_stock');
            }
        }

        // 4. Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'stock_asc':
                $query->orderBy('stock', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('stock', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        // 5. Pagination with preserved query string
        $products = $query->paginate(8)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:150'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048'],
            'suppliers' => ['nullable', 'array'],
            'suppliers.*' => ['exists:suppliers,id'],
        ], [
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'sku.required' => 'Kode SKU wajib diisi.',
            'sku.unique' => 'Kode SKU tersebut sudah digunakan oleh produk lain.',
            'name.required' => 'Nama produk pakaian wajib diisi.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.min' => 'Harga produk tidak boleh bernilai negatif.',
            'stock.required' => 'Jumlah stok awal wajib diisi.',
            'stock.min' => 'Stok awal tidak boleh bernilai negatif.',
            'minimum_stock.required' => 'Batas minimum stok wajib diisi.',
            'minimum_stock.min' => 'Batas minimum stok tidak boleh negatif.',
            'image.image' => 'File yang diunggah harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Handle Image upload
        $imageName = 'default_cloth.svg';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = 'cloth_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $imageName);
        }

        DB::transaction(function () use ($validated, $imageName, $request) {
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'sku' => strtoupper($validated['sku']),
                'name' => $validated['name'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'minimum_stock' => $validated['minimum_stock'],
                'description' => $validated['description'] ?? null,
                'image' => $imageName,
            ]);

            // Sync suppliers if selected
            if ($request->has('suppliers') && is_array($request->suppliers)) {
                $product->suppliers()->sync($request->suppliers);
            }
        });

        return redirect()->route('products.index')
            ->with('success', "Produk pakaian '{$validated['name']}' berhasil ditambahkan ke katalog!");
    }

    /**
     * Display the specified resource.
     * Shows product specs, Many-to-Many suppliers, and audit stock movements (Slide 34)
     */
    public function show(Product $product)
    {
        $product->load(['category', 'suppliers', 'stockMovements.user.profile']);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $product->load('suppliers');

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'sku' => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($product->id)],
            'name' => ['required', 'string', 'max:150'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048'],
            'suppliers' => ['nullable', 'array'],
            'suppliers.*' => ['exists:suppliers,id'],
        ], [
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'sku.required' => 'Kode SKU wajib diisi.',
            'sku.unique' => 'Kode SKU tersebut sudah digunakan oleh produk lain.',
            'name.required' => 'Nama produk pakaian wajib diisi.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.min' => 'Harga produk tidak boleh bernilai negatif.',
            'stock.required' => 'Jumlah stok awal wajib diisi.',
            'stock.min' => 'Stok awal tidak boleh bernilai negatif.',
            'minimum_stock.required' => 'Batas minimum stok wajib diisi.',
            'minimum_stock.min' => 'Batas minimum stok tidak boleh negatif.',
        ]);

        $imageName = $product->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = 'cloth_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $imageName);
        }

        DB::transaction(function () use ($product, $validated, $imageName, $request) {
            $product->update([
                'category_id' => $validated['category_id'],
                'sku' => strtoupper($validated['sku']),
                'name' => $validated['name'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'minimum_stock' => $validated['minimum_stock'],
                'description' => $validated['description'] ?? null,
                'image' => $imageName,
            ]);

            if ($request->has('suppliers')) {
                $product->suppliers()->sync($request->suppliers);
            } else {
                $product->suppliers()->detach();
            }
        });

        return redirect()->route('products.index')
            ->with('success', "Data produk pakaian '{$product->name}' berhasil diperbarui!");
    }

    /**
     * Remove the specified resource from storage.
     * Enforces ON DELETE RESTRICT relational protection if stock movements exist.
     */
    public function destroy(Product $product)
    {
        if ($product->stockMovements()->exists()) {
            return back()->with('error', "Gagal menghapus produk '{$product->name}'! Produk ini memiliki riwayat mutasi stok. Kebijakan integritas data melarang penghapusan produk berhistori (ON DELETE RESTRICT).");
        }

        $name = $product->name;
        DB::transaction(function () use ($product) {
            $product->suppliers()->detach();
            $product->delete();
        });

        return redirect()->route('products.index')
            ->with('success', "Produk pakaian '{$name}' berhasil dihapus dari sistem.");
    }
}
