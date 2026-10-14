@extends('layouts.app')

@section('title', 'Pencatatan Barang Masuk')
@section('breadcrumb', 'Mutasi Stok / Barang Masuk')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Pencatatan Barang Masuk (Stock In)</h1>
        <p class="page-subtitle">Catat penerimaan stok dari pabrik konveksi atau restok supplier ke gudang butik.</p>
    </div>
    <div>
        <a href="{{ route('stock.index') }}" class="btn btn-secondary">&larr; Kembali ke Histori</a>
    </div>
</div>

<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="card-header" style="background: #f0fdf4;">
        <h2 class="card-title" style="color: #166534;">📥 Formulir Barang Masuk</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('stock.storeIn') }}" id="form-stock-in">
            @csrf

            <!-- Product Selection -->
            <div class="form-group">
                <label for="product_id" class="form-label">Pilih Produk Pakaian <span style="color: #e11d48;">*</span></label>
                <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required autofocus>
                    <option value="">-- Pilih Busana yang Diterima --</option>
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
                <label for="quantity" class="form-label">Jumlah Barang Masuk (Unit) <span style="color: #e11d48;">*</span></label>
                <input 
                    type="number" 
                    name="quantity" 
                    id="quantity" 
                    class="form-control @error('quantity') is-invalid @enderror" 
                    value="{{ old('quantity', 10) }}" 
                    min="1" 
                    required
                >
                <div class="form-hint">Stok produk akan bertambah secara atomik di database setelah transaksi disimpan.</div>
                @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Reference Number -->
            <div class="form-group">
                <label for="reference_number" class="form-label">Nomor Referensi (Surat Jalan / Purchase Order)</label>
                <input 
                    type="text" 
                    name="reference_number" 
                    id="reference_number" 
                    class="form-control @error('reference_number') is-invalid @enderror" 
                    value="{{ old('reference_number', 'PO-VOG-' . date('ymd') . '-' . rand(100, 999)) }}" 
                    placeholder="Contoh: PO-VOG-0015"
                >
                @error('reference_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Notes -->
            <div class="form-group">
                <label for="notes" class="form-label">Catatan Transaksi / Keterangan Batch</label>
                <textarea 
                    name="notes" 
                    id="notes" 
                    rows="3" 
                    class="form-control @error('notes') is-invalid @enderror" 
                    placeholder="Contoh: Penerimaan kloter 2 dari PT Bandung Textile, kualitas jahitan lolos QC..."
                >{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <a href="{{ route('stock.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-success" id="btn-submit-stock-in">
                    📥 Simpan Barang Masuk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
