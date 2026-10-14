@extends('layouts.app')

@section('title', 'Edit Pemasok: ' . $supplier->name)
@section('breadcrumb', 'Pemasok / Edit')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Edit Data Pemasok: {{ $supplier->name }}</h1>
        <p class="page-subtitle">Perbarui kontak dan informasi operasional pemasok mitra.</p>
    </div>
    <div>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">&larr; Batal</a>
    </div>
</div>

<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">Formulir Edit Pemasok</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="form-label">Nama Perusahaan / Pabrik <span style="color: #e11d48;">*</span></label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    class="form-control @error('name') is-invalid @enderror" 
                    value="{{ old('name', $supplier->name) }}" 
                    required
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="contact_person" class="form-label">Kontak Person (PIC)</label>
                    <input 
                        type="text" 
                        name="contact_person" 
                        id="contact_person" 
                        class="form-control @error('contact_person') is-invalid @enderror" 
                        value="{{ old('contact_person', $supplier->contact_person) }}"
                    >
                    @error('contact_person')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Nomor Telepon</label>
                    <input 
                        type="text" 
                        name="phone" 
                        id="phone" 
                        class="form-control @error('phone') is-invalid @enderror" 
                        value="{{ old('phone', $supplier->phone) }}"
                    >
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    value="{{ old('email', $supplier->email) }}"
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
                >{{ old('address', $supplier->address) }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    💾 Perbarui Pemasok
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
