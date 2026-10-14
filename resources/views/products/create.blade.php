@extends('layouts.app')

@section('title', 'Tambah Busana Baru')
@section('breadcrumb', 'Katalog Produk / Tambah Busana')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Tambah Koleksi Busana Baru</h1>
        <p class="page-subtitle">Daftarkan item pakaian ke dalam database dengan relasi kategori dan pemasok mitra.</p>
    </div>
    <div>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            &larr; Kembali ke Katalog
        </a>
    </div>
</div>

<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">Formulir Pendaftaran Busana (Slide 25)</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" id="create-product-form">
            @csrf

            <div class="form-grid">
                <!-- SKU Code -->
                <div class="form-group">
                    <label for="sku" class="form-label">Kode SKU (Stock Keeping Unit) <span style="color: #e11d48;">*</span></label>
                    <input 
                        type="text" 
                        name="sku" 
                        id="sku" 
                        class="form-control @error('sku') is-invalid @enderror" 
                        value="{{ old('sku') }}" 
                        placeholder="Contoh: VOG-TSH-003" 
                        required
                    >
                    <div class="form-hint">Format identifikasi unik produk pakaian.</div>
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
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                <label for="name" class="form-label">Nama Lengkap Busana / Pakaian <span style="color: #e11d48;">*</span></label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    class="form-control @error('name') is-invalid @enderror" 
                    value="{{ old('name') }}" 
                    placeholder="Contoh: Oversized Vintage Wash Hoodie" 
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
                        value="{{ old('price', 0) }}" 
                        min="0" 
                        required
                    >
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Initial Stock -->
                <div class="form-group">
                    <label for="stock" class="form-label">Jumlah Stok Awal (Unit) <span style="color: #e11d48;">*</span></label>
                    <input 
                        type="number" 
                        name="stock" 
                        id="stock" 
                        class="form-control @error('stock') is-invalid @enderror" 
                        value="{{ old('stock', 10) }}" 
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
                        value="{{ old('minimum_stock', 5) }}" 
                        min="0" 
                        required
                    >
                    <div class="form-hint">Peringatan otomatis muncul bila stok &le; angka ini.</div>
                    @error('minimum_stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description" class="form-label">Deskripsi Spesifikasi Bahan & Karakteristik Busana</label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="3" 
                    class="form-control @error('description') is-invalid @enderror" 
                    placeholder="Tuliskan spesifikasi gramasi kain, siluet potongan, teknik sablon/pencucian..."
                >{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Image Upload -->
            <div class="form-group">
                <label for="image" class="form-label">Foto Pakaian (Opsional)</label>
                <input 
                    type="file" 
                    name="image" 
                    id="image" 
                    class="form-control @error('image') is-invalid @enderror" 
                    accept="image/*"
                >
                <div class="form-hint">Format file: JPG, PNG, WEBP, atau SVG (Maks. 2MB). Jika dikosongkan, gambar default akan digunakan.</div>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Suppliers Checkboxes (Many-to-Many - Slide 5 & 8) -->
            <div class="form-group" style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px dashed var(--border);">
                <label class="form-label">Pabrik / Vendor Pemasok Terkait (Many-to-Many Relationship)</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 0.75rem; margin-top: 0.5rem;">
                    @foreach($suppliers as $supplier)
                    <label style="display: flex; align-items: flex-start; gap: 0.65rem; background: #f8fafc; padding: 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--border); cursor: pointer;">
                        <input 
                            type="checkbox" 
                            name="suppliers[]" 
                            value="{{ $supplier->id }}" 
                            style="margin-top: 3px;"
                            {{ is_array(old('suppliers')) && in_array($supplier->id, old('suppliers')) ? 'checked' : '' }}
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
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" id="btn-submit-product">
                    💾 Simpan Busana ke Sistem
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
