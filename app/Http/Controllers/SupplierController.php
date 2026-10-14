<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index()
    {
        $suppliers = Supplier::withCount('products')
            ->orderBy('name')
            ->paginate(10);

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created supplier.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'contact_person' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:25'],
            'email' => ['nullable', 'email', 'max:100'],
            'address' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama perusahaan pemasok wajib diisi.',
            'email.email' => 'Format email pemasok tidak valid.',
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', "Pemasok '{$validated['name']}' berhasil didaftarkan.");
    }

    /**
     * Display the specified supplier.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load('products.category');

        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the supplier.
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the supplier.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'contact_person' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:25'],
            'email' => ['nullable', 'email', 'max:100'],
            'address' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama perusahaan pemasok wajib diisi.',
            'email.email' => 'Format email pemasok tidak valid.',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', "Data pemasok '{$supplier->name}' berhasil diperbarui.");
    }

    /**
     * Remove the specified supplier.
     */
    public function destroy(Supplier $supplier)
    {
        $name = $supplier->name;
        $supplier->products()->detach();
        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', "Pemasok '{$name}' berhasil dihapus.");
    }
}
