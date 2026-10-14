@extends('layouts.app')

@section('title', 'Laporan Inventaris & Valuasi Aset')
@section('breadcrumb', 'Laporan Inventaris')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Akumulasi Inventaris & Valuasi Aset</h1>
        <p class="page-subtitle">Analisis pergerakan total barang masuk, keluar, dan valuasi aset persediaan (Slide 28).</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <a href="{{ route('reports.print', request()->all()) }}" target="_blank" class="btn btn-secondary" id="btn-print-report">
            🖨️ Cetak / Unduh PDF
        </a>
    </div>
</div>

<!-- Summary Metrics Bar -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon stat-icon-indigo">👗</div>
        <div class="stat-content">
            <span class="stat-label">Model Terdaftar</span>
            <span class="stat-value">{{ $summary['total_models'] }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-emerald">📦</div>
        <div class="stat-content">
            <span class="stat-label">Total Unit di Gudang</span>
            <span class="stat-value">{{ number_format($summary['total_stock_units']) }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-blue">📥</div>
        <div class="stat-content">
            <span class="stat-label">Akumulasi Masuk</span>
            <span class="stat-value">{{ number_format($summary['total_in_units']) }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-rose">📤</div>
        <div class="stat-content">
            <span class="stat-label">Akumulasi Keluar</span>
            <span class="stat-value">{{ number_format($summary['total_out_units']) }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-amber">💎</div>
        <div class="stat-content">
            <span class="stat-label">Total Valuasi Aset</span>
            <span class="stat-value">Rp {{ number_format($summary['total_valuation'], 0, ',', '.') }}</span>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<form method="GET" action="{{ route('reports.index') }}" class="filter-bar">
    <div class="filter-group" style="flex: 2; min-width: 240px;">
        <input 
            type="text" 
            name="search" 
            class="form-control" 
            placeholder="Cari kode SKU atau nama busana..." 
            value="{{ request('search') }}"
        >
    </div>

    <div class="filter-group">
        <select name="category_id" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div style="display: flex; gap: 0.5rem;">
        <button type="submit" class="btn btn-secondary">Filter Laporan</button>
        @if(request()->anyFilled(['search', 'category_id']))
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">✕ Reset</a>
        @endif
    </div>
</form>

<!-- Aggregate Table (Slide 28 Query Implementation) -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Tabel Rincian Persediaan & Perputaran Stok Busana</h2>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode SKU</th>
                        <th>Nama Model Busana</th>
                        <th>Kategori</th>
                        <th style="text-align: right;">Harga Satuan</th>
                        <th style="text-align: center;">Total Masuk</th>
                        <th style="text-align: center;">Total Keluar</th>
                        <th style="text-align: center;">Sisa Stok</th>
                        <th style="text-align: right;">Valuasi Aset</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $row)
                    <tr>
                        <td>
                            <span class="product-sku-tag">{{ $row->sku }}</span>
                        </td>
                        <td>
                            <a href="{{ route('products.show', $row->id) }}" class="product-name-link">
                                {{ $row->product_name }}
                            </a>
                        </td>
                        <td>
                            <span class="badge badge-neutral">{{ $row->category_name }}</span>
                        </td>
                        <td style="text-align: right;">
                            Rp {{ number_format($row->price, 0, ',', '.') }}
                        </td>
                        <td style="text-align: center; color: #059669; font-weight: 600;">
                            +{{ $row->total_in }}
                        </td>
                        <td style="text-align: center; color: #e11d48; font-weight: 600;">
                            -{{ $row->total_out }}
                        </td>
                        <td style="text-align: center;">
                            <strong style="font-size: 1rem; color: {{ $row->stock <= $row->minimum_stock ? '#e11d48' : '#0f172a' }};">
                                {{ $row->stock }}
                            </strong>
                            @if($row->stock <= 0)
                                <div><span class="badge badge-danger" style="font-size: 0.65rem; padding: 1px 4px;">Habis</span></div>
                            @elseif($row->stock <= $row->minimum_stock)
                                <div><span class="badge badge-warning" style="font-size: 0.65rem; padding: 1px 4px;">Menipis</span></div>
                            @endif
                        </td>
                        <td style="text-align: right; font-weight: 700; color: #0f172a;">
                            Rp {{ number_format($row->asset_value, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2.5rem; color: #94a3b8;">
                            Tidak ada data inventaris yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr style="background: #f8fafc; font-weight: 800; border-top: 2px solid var(--border);">
                        <td colspan="4" style="text-align: right; padding: 1rem;">TOTAL KESELURUHAN:</td>
                        <td style="text-align: center; color: #059669; padding: 1rem;">+{{ number_format($summary['total_in_units']) }}</td>
                        <td style="text-align: center; color: #e11d48; padding: 1rem;">-{{ number_format($summary['total_out_units']) }}</td>
                        <td style="text-align: center; padding: 1rem;">{{ number_format($summary['total_stock_units']) }} unit</td>
                        <td style="text-align: right; color: #4338ca; font-size: 1.05rem; padding: 1rem;">
                            Rp {{ number_format($summary['total_valuation'], 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
