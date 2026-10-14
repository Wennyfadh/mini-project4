@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('breadcrumb', 'Administrasi / Pengguna')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Akun Pengguna & Hak Akses</h1>
        <p class="page-subtitle">Kelola kredensial, peran (Admin vs Staff), dan profil personal (Slide 5 & 6).</p>
    </div>
    <div>
        <a href="{{ route('users.create') }}" class="btn btn-primary" id="btn-create-user">
            + Tambah Pengguna Baru
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Username & Nama Lengkap</th>
                        <th>Peran (Role)</th>
                        <th>Kontak & Alamat</th>
                        <th>Terdaftar Sejak</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td style="color: #64748b;">#{{ $u->id }}</td>
                        <td>
                            <div style="font-weight: 700; color: #0f172a;">{{ $u->displayName }}</div>
                            <small class="product-sku-tag">{{ $u->username }}</small>
                            @if($u->email)
                                <div style="font-size: 0.74rem; color: #64748b;">{{ $u->email }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="user-role-badge role-{{ $u->role }}">
                                {{ $u->role === 'admin' ? '👑 Administrator' : '📦 Staff Gudang' }}
                            </span>
                        </td>
                        <td style="font-size: 0.82rem; color: #475569; max-width: 250px;">
                            <div>{{ $u->profile?->phone ?? '-' }}</div>
                            <small style="color: #64748b;">{{ $u->profile?->address ?? '-' }}</small>
                        </td>
                        <td style="color: #64748b; font-size: 0.82rem;">
                            {{ $u->created_at->format('d/m/Y') }}
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; gap: 0.35rem;">
                                <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-secondary" title="Edit Akun">
                                    ✏️
                                </a>
                                @if($u->id !== Auth::id())
                                <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('Hapus akun pengguna {{ $u->username }}?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus Akun">
                                        🗑️
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: #94a3b8;">
                            Belum ada akun terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="pagination-wrapper">
    <div>{{ $users->links() }}</div>
</div>
@endsection
