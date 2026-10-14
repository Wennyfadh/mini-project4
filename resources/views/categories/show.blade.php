@extends('layouts.app')

@section('title', 'Kategori: ' . $category->name)
@section('breadcrumb', 'Kategori / ' . $category->name)

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $category->name }}</h1>
        <p class="page-subtitle">{{ $category->description ?: 'Koleksi busana di bawah naungan kategori ini.' }}</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            + Tambah Busana di Kategori Ini
        </a>
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-secondary">
            ✏️ Edit Kategori
        </a>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
            &larr; Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Daftar Busana dalam Kategori Ini (One-to-Many - Slide 33)</h2>
        <span style="font-size: 0.8rem; color: #64748b;">Total {{ $category->products->count() }} busana</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">Foto</th>
                        <th>Kode SKU & Nama Busana</th>
                        <th>Harga Satuan</th>
                        <th>Sisa Stok</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($category->products as $product)
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
                        <td><strong>{{ $product->formatted_price }}</strong></td>
                        <td><strong>{{ $product->stock }}</strong> unit</td>
                        <td>
                            @if($product->stock <= 0)
                                <span class="badge badge-danger">Habis</span>
                            @elseif($product->stock <= $product->minimum_stock)
                                <span class="badge badge-warning">Menipis</span>
                            @else
                                <span class="badge badge-success">Tersedia</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-secondary">
                                Detail &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: #94a3b8;">
                            Belum ada busana dalam kategori ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
