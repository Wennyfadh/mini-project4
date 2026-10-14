@extends('layouts.app')

@section('title', 'Histori Mutasi Stok')
@section('breadcrumb', 'Histori Mutasi Stok')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Histori Mutasi Stok (Audit Jurnal)</h1>
        <p class="page-subtitle">Audit jejak pergerakan barang masuk dan barang keluar secara transparan dan terdata.</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <a href="{{ route('stock.in') }}" class="btn btn-success" id="btn-stock-in">
            📥 + Barang Masuk (In)
        </a>
        <a href="{{ route('stock.out') }}" class="btn btn-danger" id="btn-stock-out">
            📤 - Barang Keluar (Out)
        </a>
    </div>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('stock.index') }}" class="filter-bar">
    <div class="filter-group" style="flex: 2; min-width: 220px;">
        <input 
            type="text" 
            name="search" 
            class="form-control" 
            placeholder="Cari no. referensi, catatan, busana..." 
            value="{{ request('search') }}"
        >
    </div>

    <div class="filter-group">
        <select name="type" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Tipe Mutasi</option>
            <option value="IN" {{ request('type') === 'IN' ? 'selected' : '' }}>📥 Barang Masuk (IN)</option>
            <option value="OUT" {{ request('type') === 'OUT' ? 'selected' : '' }}>📤 Barang Keluar (OUT)</option>
        </select>
    </div>

    <div class="filter-group">
        <select name="product_id" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Produk Pakaian</option>
            @foreach($products as $prod)
                <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>
                    {{ $prod->name }} ({{ $prod->sku }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="filter-group" style="max-width: 150px;">
        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" title="Dari Tanggal">
    </div>

    <div class="filter-group" style="max-width: 150px;">
        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" title="Sampai Tanggal">
    </div>

    <div style="display: flex; gap: 0.5rem;">
        <button type="submit" class="btn btn-secondary">Filter</button>
        @if(request()->anyFilled(['search', 'type', 'product_id', 'from_date', 'to_date']))
            <a href="{{ route('stock.index') }}" class="btn btn-secondary" title="Reset filter">✕ Reset</a>
        @endif
    </div>
</form>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 140px;">Waktu & Tanggal</th>
                        <th>No. Referensi</th>
                        <th>Tipe</th>
                        <th>Produk Busana</th>
                        <th>Jumlah</th>
                        <th>Petugas Logistik</th>
                        <th>Catatan / Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $m)
                    <tr>
                        <td style="color: #64748b; white-space: nowrap;">
                            {{ $m->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <span class="product-sku-tag">{{ $m->reference_number ?? '-' }}</span>
                        </td>
                        <td>
                            @if($m->type === 'IN')
                                <span class="badge badge-success">📥 MASUK</span>
                            @else
                                <span class="badge badge-danger">📤 KELUAR</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('products.show', $m->product) }}" class="product-name-link">
                                {{ $m->product->name }}
                            </a>
                            <div style="font-size: 0.74rem; color: #64748b;">{{ $m->product->category->name }} ({{ $m->product->sku }})</div>
                        </td>
                        <td>
                            <strong style="font-size: 1rem; color: {{ $m->type === 'IN' ? '#059669' : '#e11d48' }};">
                                {{ $m->type === 'IN' ? '+' : '-' }}{{ $m->quantity }}
                            </strong> unit
                        </td>
                        <td>
                            <strong>{{ $m->user->displayName }}</strong>
                            <div><span class="user-role-badge role-{{ $m->user->role }}">{{ $m->user->role }}</span></div>
                        </td>
                        <td style="color: #475569; font-size: 0.84rem; max-width: 300px;">
                            {{ $m->notes ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem; color: #94a3b8;">
                            Belum ada riwayat mutasi stok yang sesuai dengan filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="pagination-wrapper">
    <div style="font-size: 0.84rem; color: #64748b;">
        Menampilkan <strong>{{ $movements->firstItem() ?? 0 }}</strong> - <strong>{{ $movements->lastItem() ?? 0 }}</strong> dari total <strong>{{ $movements->total() }}</strong> mutasi
    </div>
    <div>{{ $movements->links() }}</div>
</div>
@endsection
