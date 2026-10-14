@extends('layouts.app')

@section('title', 'Katalog Koleksi Pakaian')
@section('breadcrumb', 'Katalog Produk')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Katalog Busana & Apparel</h1>
        <p class="page-subtitle">Kelola master data pakaian, ketersediaan unit, relasi kategori dan pemasok tekstil.</p>
    </div>
    <div>
        <a href="{{ route('products.create') }}" class="btn btn-primary" id="btn-create-product">
            + Tambah Busana Baru
        </a>
    </div>
</div>

<!-- Search, Filter & Sort Bar (Slide 32, 38, 41) -->
<form method="GET" action="{{ route('products.index') }}" class="filter-bar">
    <div class="filter-group" style="flex: 2; min-width: 240px;">
        <input 
            type="text" 
            name="search" 
            class="form-control" 
            placeholder="Cari nama busana, SKU, atau deskripsi..." 
            value="{{ request('search') }}"
            id="input-search"
        >
    </div>

    <div class="filter-group">
        <select name="category_id" class="form-select" id="select-category" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <select name="status" class="form-select" id="select-status" onchange="this.form.submit()">
            <option value="">Semua Status Stok</option>
            <option value="in_stock" {{ request('status') === 'in_stock' ? 'selected' : '' }}>Tersedia (Aman)</option>
            <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>⚠️ Stok Menipis</option>
            <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>⛔ Stok Habis (0)</option>
        </select>
    </div>

    <div class="filter-group">
        <select name="sort" class="form-select" id="select-sort" onchange="this.form.submit()">
            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru Ditambahkan</option>
            <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Nama Pakaian (A - Z)</option>
            <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Nama Pakaian (Z - A)</option>
            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga Terendah &rarr; Tertinggi</option>
            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga Tertinggi &rarr; Terendah</option>
            <option value="stock_asc" {{ request('sort') === 'stock_asc' ? 'selected' : '' }}>Stok Tersedikit &rarr; Terbanyak</option>
            <option value="stock_desc" {{ request('sort') === 'stock_desc' ? 'selected' : '' }}>Stok Terbanyak &rarr; Tersedikit</option>
        </select>
    </div>

    <div style="display: flex; gap: 0.5rem;">
        <button type="submit" class="btn btn-secondary" id="btn-filter-submit">Filter</button>
        @if(request()->anyFilled(['search', 'category_id', 'status', 'sort']))
            <a href="{{ route('products.index') }}" class="btn btn-secondary" title="Reset filter" id="btn-filter-reset">✕ Reset</a>
        @endif
    </div>
</form>

<!-- Products Table (Slide 39) -->
<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Foto</th>
                        <th>Kode SKU & Nama Busana</th>
                        <th>Kategori</th>
                        <th>Harga Satuan</th>
                        <th>Sisa Stok</th>
                        <th>Ambang Min</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-thumb">
                        </td>
                        <td>
                            <div class="product-meta">
                                <div>
                                    <span class="product-sku-tag">{{ $product->sku }}</span>
                                    <div>
                                        <a href="{{ route('products.show', $product) }}" class="product-name-link">
                                            {{ $product->name }}
                                        </a>
                                    </div>
                                    <small style="color: #64748b; font-size: 0.76rem;">
                                        Pemasok: {{ $product->suppliers->pluck('name')->implode(', ') ?: 'Belum terhubung' }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-neutral">
                                {{ $product->category->name }}
                            </span>
                        </td>
                        <td>
                            <strong>{{ $product->formatted_price }}</strong>
                        </td>
                        <td>
                            <strong style="font-size: 1.05rem;">{{ $product->stock }}</strong> unit
                        </td>
                        <td style="color: #64748b;">
                            {{ $product->minimum_stock }} unit
                        </td>
                        <td>
                            @if($product->stock <= 0)
                                <span class="badge badge-danger">Habis</span>
                            @elseif($product->stock <= $product->minimum_stock)
                                <span class="badge badge-warning">Menipis</span>
                            @else
                                <span class="badge badge-success">Tersedia</span>
                            @endif
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; gap: 0.35rem;">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-secondary" title="Lihat Detail">
                                    👁️
                                </a>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-secondary" title="Edit Busana">
                                    ✏️
                                </a>
                                <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk {{ $product->name }}?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus Produk">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem; color: #94a3b8;">
                            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🛍️</div>
                            <p style="font-size: 1.05rem; font-weight: 600; color: #475569;">Belum ada data busana yang cocok dengan pencarian.</p>
                            <p style="font-size: 0.85rem; margin-top: 0.25rem;">Coba ganti kata kunci atau atur ulang filter pencarian Anda.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination (Slide 38 & 39) -->
<div class="pagination-wrapper">
    <div style="font-size: 0.84rem; color: #64748b;">
        Menampilkan <strong>{{ $products->firstItem() ?? 0 }}</strong> - <strong>{{ $products->lastItem() ?? 0 }}</strong> dari total <strong>{{ $products->total() }}</strong> busana
    </div>
    <div>
        {{ $products->links() }}
    </div>
</div>
@endsection
