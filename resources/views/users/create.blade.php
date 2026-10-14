@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')
@section('breadcrumb', 'Pengguna / Tambah')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Tambah Pengguna Baru</h1>
        <p class="page-subtitle">Daftarkan akun administrator atau staf operasional pergudangan baru.</p>
    </div>
    <div>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">&larr; Kembali</a>
    </div>
</div>

<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">Formulir Pengguna & Profil</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="username" class="form-label">Username <span style="color: #e11d48;">*</span></label>
                    <input 
                        type="text" 
                        name="username" 
                        id="username" 
                        class="form-control @error('username') is-invalid @enderror" 
                        value="{{ old('username') }}" 
                        placeholder="Contoh: staff2" 
                        required 
                        autofocus
                    >
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="role" class="form-label">Peran (Role Akses) <span style="color: #e11d48;">*</span></label>
                    <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff Gudang (Akses Operasional)</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator (Akses Penuh)</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="full_name" class="form-label">Nama Lengkap & Jabatan <span style="color: #e11d48;">*</span></label>
                <input 
                    type="text" 
                    name="full_name" 
                    id="full_name" 
                    class="form-control @error('full_name') is-invalid @enderror" 
                    value="{{ old('full_name') }}" 
                    placeholder="Contoh: Rian Hidayat (Inventory Officer)" 
                    required
                >
                @error('full_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email (Opsional)</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        value="{{ old('email') }}" 
                        placeholder="rian@vogueora.com"
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi (Password) <span style="color: #e11d48;">*</span></label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        placeholder="Minimal 6 karakter" 
                        required
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Nomor Telepon / WhatsApp</label>
                <input 
                    type="text" 
                    name="phone" 
                    id="phone" 
                    class="form-control @error('phone') is-invalid @enderror" 
                    value="{{ old('phone') }}" 
                    placeholder="08xxxxxxxxxx"
                >
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="address" class="form-label">Alamat Domisili / Penugasan</label>
                <textarea 
                    name="address" 
                    id="address" 
                    rows="2" 
                    class="form-control @error('address') is-invalid @enderror" 
                    placeholder="Alamat tempat tinggal atau divisi gudang..."
                >{{ old('address') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    💾 Daftarkan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
