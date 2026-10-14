@extends('layouts.app')

@section('title', 'Pemasok: ' . $supplier->name)
@section('breadcrumb', 'Pemasok / ' . $supplier->name)

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $supplier->name }}</h1>
        <p class="page-subtitle">Rincian kontak pabrik dan busana yang dipasok ke butik Vogueora Apparel.</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-secondary">
            ✏️ Edit Pemasok
        </a>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
            &larr; Kembali
        </a>
    </div>
</div>

<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <h2 class="card-title">Informasi Pemasok</h2>
    </div>
    <div class="card-body">
        <div class="spec-list">
            <div class="spec-term">Kontak Person (PIC)</div>
            <div class="spec-val">{{ $supplier->contact_person ?? '-' }}</div>

            <div class="spec-term">Nomor Telepon</div>
            <div class="spec-val">{{ $supplier->phone ?? '-' }}</div>

            <div class="spec-term">Alamat Email</div>
            <div class="spec-val">{{ $supplier->email ?? '-' }}</div>

            <div class="spec-term">Alamat Kantor/Pabrik</div>
            <div class="spec-val" style="font-weight: 400;">{{ $supplier->address ?? '-' }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Daftar Busana yang Dipasok (Many-to-Many - Slide 5 & 8)</h2>
        <span style="font-size: 0.8rem; color: #64748b;">Total {{ $supplier->products->count() }} item</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Kode SKU & Nama Busana</th>
                        <th>Kategori</th>
                        <th>Harga Jual Butik</th>
                        <th>Harga Beli Mitra</th>
                        <th>Sisa Stok</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($supplier->products as $product)
                    <tr>
                        <td>
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-thumb">
                        </td>
                        <td>
                            <span class="product-sku-tag">{{ $product->sku }}</span>
                            <div>
                                <a href="{{ route('products.show', $product) }}" class="product-name-link">
                                    {{ $product->name }}
                                </a>
                            </div>
                        </td>
                        <td>{{ $product->category->name }}</td>
                        <td><strong>{{ $product->formatted_price }}</strong></td>
                        <td>
                            @if($product->pivot->supplier_price)
                                <strong style="color: #059669;">
                                    Rp {{ number_format($product->pivot->supplier_price, 0, ',', '.') }}
                                </strong>
                            @else
                                <span style="color: #94a3b8;">-</span>
                            @endif
                        </td>
                        <td><strong>{{ $product->stock }}</strong> unit</td>
                        <td style="text-align: right;">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-secondary">
                                Detail &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: #94a3b8;">
                            Belum ada busana yang dipetakan ke pemasok ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
