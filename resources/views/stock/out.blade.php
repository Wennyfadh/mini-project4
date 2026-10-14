@extends('layouts.app')

@section('title', 'Pencatatan Barang Keluar')
@section('breadcrumb', 'Mutasi Stok / Barang Keluar')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Pencatatan Barang Keluar (Stock Out)</h1>
        <p class="page-subtitle">Catat distribusi pakaian ke toko ritel, pesanan butik online, atau konsinyasi mitra.</p>
    </div>
    <div>
        <a href="{{ route('stock.index') }}" class="btn btn-secondary">&larr; Kembali ke Histori</a>
    </div>
</div>

<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="card-header" style="background: #fff1f2;">
        <h2 class="card-title" style="color: #be123c;">📤 Formulir Barang Keluar</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('stock.storeOut') }}" id="form-stock-out">
            @csrf

            <!-- Product Selection -->
            <div class="form-group">
                <label for="product_id" class="form-label">Pilih Produk Pakaian <span style="color: #e11d48;">*</span></label>
                <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required autofocus>
                    <option value="">-- Pilih Busana yang Dikeluarkan --</option>
                    @foreach($products as $prod)
                        <option 
                            value="{{ $prod->id }}" 
                            {{ (old('product_id', request('product_id')) == $prod->id) ? 'selected' : '' }}
                            data-stock="{{ $prod->stock }}"
                        >
                            {{ $prod->name }} [{{ $prod->sku }}] &mdash; Sisa Stok: {{ $prod->stock }} unit
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Quantity -->
            <div class="form-group">
                <label for="quantity" class="form-label">Jumlah Barang Keluar (Unit) <span style="color: #e11d48;">*</span></label>
                <input 
                    type="number" 
                    name="quantity" 
                    id="quantity" 
                    class="form-control @error('quantity') is-invalid @enderror" 
                    value="{{ old('quantity', 1) }}" 
                    min="1" 
                    required
                >
                <div class="form-hint" style="color: #b45309;">
                    ⚠️ Perhatian: Jumlah permintaan tidak boleh melampaui sisa unit stok di gudang.
                </div>
                @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Reference Number -->
            <div class="form-group">
                <label for="reference_number" class="form-label">Nomor Surat Order / Invoice / Faktur</label>
                <input 
                    type="text" 
                    name="reference_number" 
                    id="reference_number" 
                    class="form-control @error('reference_number') is-invalid @enderror" 
                    value="{{ old('reference_number', 'SO-VOG-' . date('ymd') . '-' . rand(100, 999)) }}" 
                    placeholder="Contoh: SO-VOG-0150"
                >
                @error('reference_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Notes -->
            <div class="form-group">
                <label for="notes" class="form-label">Catatan Pengeluaran Barang / Tujuan Distribusi</label>
                <textarea 
                    name="notes" 
                    id="notes" 
                    rows="3" 
                    class="form-control @error('notes') is-invalid @enderror" 
                    placeholder="Contoh: Pengiriman order e-commerce customer butik Senopati..."
                >{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <a href="{{ route('stock.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-danger" id="btn-submit-stock-out">
                    📤 Simpan Barang Keluar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
