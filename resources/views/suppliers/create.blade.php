@extends('layouts.app')

@section('title', 'Tambah Pemasok Baru')
@section('breadcrumb', 'Pemasok / Tambah')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Tambah Pemasok / Pabrik Baru</h1>
        <p class="page-subtitle">Daftarkan mitra penyedia kain, garmen, dan aksesoris butik.</p>
    </div>
    <div>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">&larr; Kembali</a>
    </div>
</div>

<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">Formulir Pemasok</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.store') }}">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nama Perusahaan / Pabrik <span style="color: #e11d48;">*</span></label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    class="form-control @error('name') is-invalid @enderror" 
                    value="{{ old('name') }}" 
                    placeholder="Contoh: PT Solo Tekstil Sentosa" 
                    required 
                    autofocus
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="contact_person" class="form-label">Nama Kontak Person (PIC)</label>
                    <input 
                        type="text" 
                        name="contact_person" 
                        id="contact_person" 
                        class="form-control @error('contact_person') is-invalid @enderror" 
                        value="{{ old('contact_person') }}" 
                        placeholder="Contoh: Bambang Irawan"
                    >
                    @error('contact_person')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Nomor Telepon / WhatsApp</label>
                    <input 
                        type="text" 
                        name="phone" 
                        id="phone" 
                        class="form-control @error('phone') is-invalid @enderror" 
                        value="{{ old('phone') }}" 
                        placeholder="Contoh: 021-8989123"
                    >
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email Resmi</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    value="{{ old('email') }}" 
                    placeholder="Contoh: sales@pabrikteks.com"
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="address" class="form-label">Alamat Kantor / Pabrik</label>
                <textarea 
                    name="address" 
                    id="address" 
                    rows="3" 
                    class="form-control @error('address') is-invalid @enderror" 
                    placeholder="Kawasan industri, nama jalan, kota..."
                >{{ old('address') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    💾 Simpan Pemasok
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
