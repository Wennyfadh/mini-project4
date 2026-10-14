@extends('layouts.app')

@section('title', 'Pemasok & Pabrik Garmen')
@section('breadcrumb', 'Pemasok (Suppliers)')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Daftar Pabrik & Pemasok Mitra</h1>
        <p class="page-subtitle">Pemasok bahan tekstil, konveksi garmen, dan aksesoris butik (Relasi Many-to-Many).</p>
    </div>
    <div>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary" id="btn-create-supplier">
            + Tambah Pemasok Baru
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Nama Perusahaan</th>
                        <th>Kontak Person</th>
                        <th>Telepon & Email</th>
                        <th>Alamat Pabrik</th>
                        <th>Katalog Terhubung</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                    <tr>
                        <td style="color: #64748b;">#{{ $supplier->id }}</td>
                        <td>
                            <a href="{{ route('suppliers.show', $supplier) }}" style="font-weight: 700; color: #0f172a; text-decoration: none;">
                                {{ $supplier->name }}
                            </a>
                        </td>
                        <td>{{ $supplier->contact_person ?? '-' }}</td>
                        <td>
                            <div>{{ $supplier->phone ?? '-' }}</div>
                            <small style="color: #64748b;">{{ $supplier->email ?? '-' }}</small>
                        </td>
                        <td style="color: #64748b; font-size: 0.84rem; max-width: 280px;">
                            {{ $supplier->address ?? '-' }}
                        </td>
                        <td>
                            <span class="badge badge-neutral">
                                {{ $supplier->products_count }} item busana
                            </span>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; gap: 0.35rem;">
                                <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-sm btn-secondary" title="Lihat Detail">
                                    👁️
                                </a>
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-secondary" title="Edit">
                                    ✏️
                                </a>
                                <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" onsubmit="return confirm('Hapus data pemasok {{ $supplier->name }}?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2.5rem; color: #94a3b8;">
                            Belum ada pabrik/pemasok yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="pagination-wrapper">
    <div>{{ $suppliers->links() }}</div>
</div>
@endsection
