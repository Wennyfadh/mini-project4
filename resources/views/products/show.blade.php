@extends('layouts.app')

@section('title', 'Detail Busana: ' . $product->name)
@section('breadcrumb', 'Katalog Produk / ' . $product->sku)

@section('content')
<div class="page-header">
    <div>
        <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 0.25rem;">
            <span class="product-sku-tag">{{ $product->sku }}</span>
            <span class="badge badge-neutral">{{ $product->category->name }}</span>
            @if($product->stock <= 0)
                <span class="badge badge-danger">Habis</span>
            @elseif($product->stock <= $product->minimum_stock)
                <span class="badge badge-warning">Stok Menipis</span>
            @else
                <span class="badge badge-success">Stok Aman</span>
            @endif
        </div>
        <h1 class="page-title">{{ $product->name }}</h1>
    </div>
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a href="{{ route('stock.in', ['product_id' => $product->id]) }}" class="btn btn-success">
            + Tambah Stok (In)
        </a>
        <a href="{{ route('stock.out', ['product_id' => $product->id]) }}" class="btn btn-danger">
            - Catat Keluar (Out)
        </a>
        <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary">
            ✏️ Edit Busana
        </a>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            &larr; Kembali
        </a>
    </div>
</div>

<div class="detail-grid">
    <!-- Left Column: Photo & Quick Status -->
    <div>
        <div class="detail-img-box">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                <div style="font-size: 0.78rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Harga Eceran Butik</div>
                <div style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-top: 0.15rem;">
                    {{ $product->formatted_price }}
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Specs & Suppliers -->
    <div>
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Spesifikasi Busana & Status Persediaan</h2>
            </div>
            <div class="card-body">
                <div class="spec-list">
                    <div class="spec-term">Kategori Produk</div>
                    <div class="spec-val">{{ $product->category->name }}</div>

                    <div class="spec-term">Jumlah Sisa Stok</div>
                    <div class="spec-val" style="font-size: 1.1rem; color: {{ $product->stock <= $product->minimum_stock ? '#e11d48' : '#059669' }};">
                        {{ $product->stock }} unit
                    </div>

                    <div class="spec-term">Batas Ambang Min.</div>
                    <div class="spec-val">{{ $product->minimum_stock }} unit</div>

                    <div class="spec-term">Valuasi Stok Item</div>
                    <div class="spec-val">
                        Rp {{ number_format($product->stock * $product->price, 0, ',', '.') }}
                    </div>

                    <div class="spec-term">Deskripsi Bahan</div>
                    <div class="spec-val" style="font-weight: 400; line-height: 1.6; color: #334155;">
                        {{ $product->description ?: 'Belum ada catatan deskripsi spesifikasi bahan.' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Many-to-Many Suppliers Table (Slide 5 & 8) -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Pabrik & Pemasok Mitra (Many-to-Many Relationship)</h2>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama Perusahaan Pemasok</th>
                                <th>Kontak Person</th>
                                <th>Telepon / Email</th>
                                <th>Estimasi Harga Beli</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product->suppliers as $supplier)
                            <tr>
                                <td>
                                    <strong>{{ $supplier->name }}</strong>
                                    <div style="font-size: 0.75rem; color: #64748b;">{{ $supplier->address }}</div>
                                </td>
                                <td>{{ $supplier->contact_person ?? '-' }}</td>
                                <td>
                                    <div>{{ $supplier->phone ?? '-' }}</div>
                                    <small style="color: #64748b;">{{ $supplier->email ?? '-' }}</small>
                                </td>
                                <td>
                                    @if($supplier->pivot->supplier_price)
                                        <strong style="color: #059669;">
                                            Rp {{ number_format($supplier->pivot->supplier_price, 0, ',', '.') }}
                                        </strong>
                                    @else
                                        <span style="color: #94a3b8;">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: #94a3b8; padding: 1.5rem;">
                                    Belum ada pabrik pemasok yang dihubungkan dengan produk ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Audit History specifically for this product -->
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <h2 class="card-title">Riwayat Mutasi Stok Busana (Audit Jurnal)</h2>
        <span style="font-size: 0.8rem; color: #64748b;">Total {{ $product->stockMovements->count() }} transaksi</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Waktu Transaksi</th>
                        <th>Kode Referensi</th>
                        <th>Tipe Mutasi</th>
                        <th>Jumlah Unit</th>
                        <th>Petugas Logistik</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($product->stockMovements as $movement)
                    <tr>
                        <td style="color: #64748b;">
                            {{ $movement->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td>
                            <span class="product-sku-tag">{{ $movement->reference_number ?? '-' }}</span>
                        </td>
                        <td>
                            @if($movement->type === 'IN')
                                <span class="badge badge-success">📥 MASUK</span>
                            @else
                                <span class="badge badge-danger">📤 KELUAR</span>
                            @endif
                        </td>
                        <td>
                            <strong style="color: {{ $movement->type === 'IN' ? '#059669' : '#e11d48' }};">
                                {{ $movement->type === 'IN' ? '+' : '-' }}{{ $movement->quantity }}
                            </strong>
                        </td>
                        <td>
                            <strong>{{ $movement->user->displayName }}</strong>
                            <div style="font-size: 0.72rem; color: #64748b;">{{ $movement->user->role }}</div>
                        </td>
                        <td style="color: #64748b;">
                            {{ $movement->notes ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: #94a3b8;">
                            Belum ada riwayat mutasi stok untuk busana ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
