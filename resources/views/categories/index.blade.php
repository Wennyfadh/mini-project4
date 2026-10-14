@extends('layouts.app')

@section('title', 'Kategori Busana')
@section('breadcrumb', 'Kategori Busana')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Master Kategori Busana</h1>
        <p class="page-subtitle">Pengelompokan jenis pakaian butik untuk kemudahan filtering dan pelaporan.</p>
    </div>
    <div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary" id="btn-create-category">
            + Tambah Kategori
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
                        <th>Nama Kategori</th>
                        <th>Deskripsi Koleksi</th>
                        <th>Jumlah Busana</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td style="color: #64748b;">#{{ $category->id }}</td>
                        <td>
                            <a href="{{ route('categories.show', $category) }}" style="font-weight: 700; color: #0f172a; text-decoration: none;">
                                {{ $category->name }}
                            </a>
                        </td>
                        <td style="color: #64748b; font-size: 0.84rem; max-width: 400px;">
                            {{ $category->description ?? '-' }}
                        </td>
                        <td>
                            <span class="badge badge-neutral">
                                {{ $category->products_count }} item busana
                            </span>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; gap: 0.35rem;">
                                <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-secondary" title="Lihat Koleksi">
                                    👁️
                                </a>
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-secondary" title="Edit">
                                    ✏️
                                </a>
                                <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori {{ $category->name }}?')" style="display: inline;">
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
                        <td colspan="5" style="text-align: center; padding: 2.5rem; color: #94a3b8;">
                            Belum ada kategori yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="pagination-wrapper">
    <div>{{ $categories->links() }}</div>
</div>
@endsection
