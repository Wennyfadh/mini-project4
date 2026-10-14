@extends('layouts.app')

@section('title', 'Dashboard Persediaan')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard Persediaan Butik</h1>
        <p class="page-subtitle">Ringkasan performa inventaris, status ketersediaan barang, dan audit mutasi terkini.</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <a href="{{ route('products.create') }}" class="btn btn-primary" id="btn-add-product">
            + Tambah Busana Baru
        </a>
    </div>
</div>

<!-- KPI Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon stat-icon-indigo">👗</div>
        <div class="stat-content">
            <span class="stat-label">Model Busana</span>
            <span class="stat-value">{{ $totalProducts }}</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-emerald">💰</div>
        <div class="stat-content">
            <span class="stat-label">Valuasi Aset Stok</span>
            <span class="stat-value">Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-amber">⚠️</div>
        <div class="stat-content">
            <span class="stat-label">Stok Menipis</span>
            <span class="stat-value" style="color: #d97706;">{{ $lowStockCount }}</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-blue">📥</div>
        <div class="stat-content">
            <span class="stat-label">Total Masuk (In)</span>
            <span class="stat-value">{{ number_format($totalIn) }}</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-rose">📤</div>
        <div class="stat-content">
            <span class="stat-label">Total Keluar (Out)</span>
            <span class="stat-value">{{ number_format($totalOut) }}</span>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    <!-- Stock Distribution Chart -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-header">
            <h2 class="card-title">Distribusi Unit Stok per Kategori</h2>
            <span style="font-size: 0.78rem; color: #64748b;">Visualisasi Persediaan</span>
        </div>
        <div class="card-body">
            <div style="height: 280px; position: relative;">
                <canvas id="categoryStockChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert Box -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-header" style="background: #fffbeb;">
            <h2 class="card-title" style="color: #92400e;">⚠️ Perhatian: Stok Menipis</h2>
            <a href="{{ route('products.index', ['status' => 'low_stock']) }}" style="font-size: 0.78rem; color: #b45309; text-decoration: none; font-weight: 600;">
                Lihat Semua &rarr;
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Pakaian</th>
                            <th>Sisa</th>
                            <th>Min</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockProducts as $low)
                        <tr>
                            <td>
                                <strong style="font-size: 0.82rem; display: block;">{{ $low->name }}</strong>
                                <span class="product-sku-tag">{{ $low->sku }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $low->stock == 0 ? 'badge-danger' : 'badge-warning' }}">
                                    {{ $low->stock }}
                                </span>
                            </td>
                            <td style="color: #64748b;">{{ $low->minimum_stock }}</td>
                            <td>
                                <a href="{{ route('stock.in', ['product_id' => $low->id]) }}" class="btn btn-sm btn-primary" style="padding: 2px 8px; font-size: 0.72rem;">
                                    + Restok
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #059669; padding: 2rem;">
                                ✓ Semua stok pakaian dalam kondisi aman!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Recent Stock Movements Audit History -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Jurnal Mutasi Stok Terakhir</h2>
        <a href="{{ route('stock.index') }}" class="btn btn-sm btn-secondary">
            Buka Histori Lengkap &rarr;
        </a>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Waktu Transaksi</th>
                        <th>Kode Referensi</th>
                        <th>Tipe</th>
                        <th>Nama Pakaian</th>
                        <th>Jumlah</th>
                        <th>Petugas Gudang</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentMovements as $m)
                    <tr>
                        <td style="white-space: nowrap; color: #64748b;">
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
                            <strong>{{ $m->product->name }}</strong>
                            <div style="font-size: 0.75rem; color: #64748b;">{{ $m->product->category->name }}</div>
                        </td>
                        <td>
                            <strong style="font-size: 0.95rem; color: {{ $m->type === 'IN' ? '#059669' : '#e11d48' }};">
                                {{ $m->type === 'IN' ? '+' : '-' }}{{ $m->quantity }}
                            </strong>
                        </td>
                        <td>
                            <div style="font-weight: 600; font-size: 0.82rem;">
                                {{ $m->user->displayName }}
                            </div>
                            <span class="user-role-badge role-{{ $m->user->role }}">{{ $m->user->role }}</span>
                        </td>
                        <td style="color: #64748b; font-size: 0.82rem;">
                            {{ $m->notes ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: #94a3b8;">
                            Belum ada riwayat transaksi mutasi stok.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const categoryLabels = {!! json_encode($categoryStats->pluck('name')) !!};
    const categoryStockData = {!! json_encode($categoryStats->pluck('stock')) !!};

    const ctx = document.getElementById('categoryStockChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: categoryLabels,
            datasets: [{
                label: 'Jumlah Stok Unit',
                data: categoryStockData,
                backgroundColor: [
                    'rgba(79, 70, 229, 0.8)',
                    'rgba(14, 165, 233, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(236, 72, 153, 0.8)',
                    'rgba(139, 92, 246, 0.8)'
                ],
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });
</script>
@endpush
@endsection
