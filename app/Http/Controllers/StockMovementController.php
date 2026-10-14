<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    /**
     * Display stock movement audit history.
     */
    public function index(Request $request)
    {
        $query = StockMovement::with(['product.category', 'user.profile']);

        // Filter by Type (IN / OUT)
        if ($request->filled('type') && in_array($request->type, ['IN', 'OUT'])) {
            $query->where('type', $request->type);
        }

        // Filter by Product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Search reference or notes
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                  });
            });
        }

        // Date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $movements = $query->latest()->paginate(10)->withQueryString();
        $products = Product::orderBy('name')->get();

        return view('stock.index', compact('movements', 'products'));
    }

    /**
     * Show form for Barang Masuk (Stock In).
     */
    public function createIn()
    {
        $products = Product::with('category')->orderBy('name')->get();

        return view('stock.in', compact('products'));
    }

    /**
     * Store Barang Masuk (Stock In) transaction.
     */
    public function storeIn(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reference_number' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ], [
            'product_id.required' => 'Pilih produk pakaian yang akan ditambah stoknya.',
            'product_id.exists' => 'Produk pakaian yang dipilih tidak valid.',
            'quantity.required' => 'Jumlah barang masuk wajib diisi.',
            'quantity.min' => 'Jumlah barang masuk minimal 1 unit.',
        ]);

        $productName = '';
        $newStock = 0;

        DB::transaction(function () use ($validated, &$productName, &$newStock) {
            $product = Product::lockForUpdate()->findOrFail($validated['product_id']);
            $productName = $product->name;

            // Increment atomic
            $product->increment('stock', $validated['quantity']);
            $newStock = $product->fresh()->stock;

            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'IN',
                'quantity' => $validated['quantity'],
                'reference_number' => $validated['reference_number'] ?: ('IN-' . date('YmdHis')),
                'notes' => $validated['notes'],
            ]);
        });

        return redirect()->route('stock.index')
            ->with('success', "Transaksi Barang Masuk berhasil dicatat! Stok '{$productName}' bertambah {$validated['quantity']} unit (Total stok saat ini: {$newStock} unit).");
    }

    /**
     * Show form for Barang Keluar (Stock Out).
     */
    public function createOut()
    {
        $products = Product::with('category')->orderBy('name')->get();

        return view('stock.out', compact('products'));
    }

    /**
     * Store Barang Keluar (Stock Out) transaction.
     * Enforces core business rule: stock must NOT be exceeded or go below 0!
     */
    public function storeOut(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reference_number' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ], [
            'product_id.required' => 'Pilih produk pakaian yang akan dikeluarkan dari stok.',
            'product_id.exists' => 'Produk pakaian yang dipilih tidak valid.',
            'quantity.required' => 'Jumlah barang keluar wajib diisi.',
            'quantity.min' => 'Jumlah barang keluar minimal 1 unit.',
        ]);

        $productName = '';
        $newStock = 0;

        try {
            DB::transaction(function () use ($validated, &$productName, &$newStock) {
                $product = Product::lockForUpdate()->findOrFail($validated['product_id']);
                $productName = $product->name;

                // Business Rule Validation: Requested quantity > Available stock
                if ($product->stock < $validated['quantity']) {
                    throw new \Exception("Stok tidak mencukupi! Sisa stok saat ini hanya {$product->stock} unit, sedangkan jumlah yang diminta adalah {$validated['quantity']} unit.");
                }

                // Decrement atomic
                $product->decrement('stock', $validated['quantity']);
                $newStock = $product->fresh()->stock;

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'OUT',
                    'quantity' => $validated['quantity'],
                    'reference_number' => $validated['reference_number'] ?: ('OUT-' . date('YmdHis')),
                    'notes' => $validated['notes'],
                ]);
            });
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('stock.index')
            ->with('success', "Transaksi Barang Keluar berhasil dicatat! Stok '{$productName}' berkurang {$validated['quantity']} unit (Sisa stok: {$newStock} unit).");
    }
}
