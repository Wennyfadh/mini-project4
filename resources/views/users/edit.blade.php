@extends('layouts.app')

@section('title', 'Edit Pengguna: ' . $user->username)
@section('breadcrumb', 'Pengguna / Edit')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Edit Pengguna: {{ $user->username }}</h1>
        <p class="page-subtitle">Perbarui profil pengguna, peran akses, atau ganti kata sandi.</p>
    </div>
    <div>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">&larr; Batal</a>
    </div>
</div>

<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">Formulir Edit Pengguna</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label for="username" class="form-label">Username <span style="color: #e11d48;">*</span></label>
                    <input 
                        type="text" 
                        name="username" 
                        id="username" 
                        class="form-control @error('username') is-invalid @enderror" 
                        value="{{ old('username', $user->username) }}" 
                        required
                    >
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="role" class="form-label">Peran (Role Akses) <span style="color: #e11d48;">*</span></label>
                    <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff Gudang (Akses Operasional)</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator (Akses Penuh)</option>
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
                    value="{{ old('full_name', $user->displayName) }}" 
                    required
                >
                @error('full_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        value="{{ old('email', $user->email) }}"
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi Baru (Opsional)</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        placeholder="Biarkan kosong jika tidak ingin diubah"
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Nomor Telepon</label>
                <input 
                    type="text" 
                    name="phone" 
                    id="phone" 
                    class="form-control @error('phone') is-invalid @enderror" 
                    value="{{ old('phone', $user->profile?->phone) }}"
                >
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="address" class="form-label">Alamat Domisili</label>
                <textarea 
                    name="address" 
                    id="address" 
                    rows="2" 
                    class="form-control @error('address') is-invalid @enderror"
                >{{ old('address', $user->profile?->address) }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    💾 Perbarui Akun Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
