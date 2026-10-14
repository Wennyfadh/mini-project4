@extends('layouts.app')

@section('title', 'Perbarui Busana: ' . $product->name)
@section('breadcrumb', 'Katalog Produk / Edit Busana')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Perbarui Data Busana: {{ $product->name }}</h1>
        <p class="page-subtitle">Modifikasi informasi SKU, harga jual, stok, atau keterkaitan dengan supplier pabrik.</p>
    </div>
    <div>
        <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">
            &larr; Batal & Kembali
        </a>
    </div>
</div>

<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">Formulir Pembaruan Busana #{{ $product->sku }}</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <!-- SKU Code -->
                <div class="form-group">
                    <label for="sku" class="form-label">Kode SKU <span style="color: #e11d48;">*</span></label>
                    <input 
                        type="text" 
                        name="sku" 
                        id="sku" 
                        class="form-control @error('sku') is-invalid @enderror" 
                        value="{{ old('sku', $product->sku) }}" 
                        required
                    >
                    @error('sku')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Category -->
                <div class="form-group">
                    <label for="category_id" class="form-label">Kategori Busana <span style="color: #e11d48;">*</span></label>
                    <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Product Name -->
            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap Busana <span style="color: #e11d48;">*</span></label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    class="form-control @error('name') is-invalid @enderror" 
                    value="{{ old('name', $product->name) }}" 
                    required
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-grid">
                <!-- Price -->
                <div class="form-group">
                    <label for="price" class="form-label">Harga Satuan (Rupiah) <span style="color: #e11d48;">*</span></label>
                    <input 
                        type="number" 
                        step="1000" 
                        name="price" 
                        id="price" 
                        class="form-control @error('price') is-invalid @enderror" 
                        value="{{ old('price', (int)$product->price) }}" 
                        min="0" 
                        required
                    >
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Stock -->
                <div class="form-group">
                    <label for="stock" class="form-label">Jumlah Stok Saat Ini (Unit) <span style="color: #e11d48;">*</span></label>
                    <input 
                        type="number" 
                        name="stock" 
                        id="stock" 
                        class="form-control @error('stock') is-invalid @enderror" 
                        value="{{ old('stock', $product->stock) }}" 
                        min="0" 
                        required
                    >
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Minimum Stock -->
                <div class="form-group">
                    <label for="minimum_stock" class="form-label">Batas Ambang Stok Minimum <span style="color: #e11d48;">*</span></label>
                    <input 
                        type="number" 
                        name="minimum_stock" 
                        id="minimum_stock" 
                        class="form-control @error('minimum_stock') is-invalid @enderror" 
                        value="{{ old('minimum_stock', $product->minimum_stock) }}" 
                        min="0" 
                        required
                    >
                    @error('minimum_stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description" class="form-label">Deskripsi Spesifikasi Bahan</label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="3" 
                    class="form-control @error('description') is-invalid @enderror"
                >{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Image Upload & Preview -->
            <div class="form-group">
                <label class="form-label">Foto Pakaian</label>
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem;">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                    <div style="font-size: 0.8rem; color: #64748b;">
                        File saat ini: <code>{{ $product->image }}</code><br>
                        Unggah file baru di bawah ini bila ingin mengganti foto.
                    </div>
                </div>
                <input 
                    type="file" 
                    name="image" 
                    id="image" 
                    class="form-control @error('image') is-invalid @enderror" 
                    accept="image/*"
                >
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Suppliers Checkboxes -->
            @php
                $selectedSuppliers = old('suppliers', $product->suppliers->pluck('id')->toArray());
            @endphp
            <div class="form-group" style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px dashed var(--border);">
                <label class="form-label">Pemasok Mitra Terkait (Many-to-Many)</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 0.75rem; margin-top: 0.5rem;">
                    @foreach($suppliers as $supplier)
                    <label style="display: flex; align-items: flex-start; gap: 0.65rem; background: #f8fafc; padding: 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--border); cursor: pointer;">
                        <input 
                            type="checkbox" 
                            name="suppliers[]" 
                            value="{{ $supplier->id }}" 
                            style="margin-top: 3px;"
                            {{ in_array($supplier->id, $selectedSuppliers) ? 'checked' : '' }}
                        >
                        <div>
                            <strong style="font-size: 0.84rem; display: block;">{{ $supplier->name }}</strong>
                            <small style="color: #64748b; font-size: 0.74rem;">{{ $supplier->contact_person }} ({{ $supplier->phone }})</small>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 2rem;">
                <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    💾 Perbarui Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
