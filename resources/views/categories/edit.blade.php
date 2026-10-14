@extends('layouts.app')

@section('title', 'Edit Kategori: ' . $category->name)
@section('breadcrumb', 'Kategori / Edit')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Edit Kategori: {{ $category->name }}</h1>
        <p class="page-subtitle">Perbarui nama dan deskripsi kategori busana.</p>
    </div>
    <div>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">&larr; Kembali</a>
    </div>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">Formulir Edit Kategori</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="form-label">Nama Kategori <span style="color: #e11d48;">*</span></label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    class="form-control @error('name') is-invalid @enderror" 
                    value="{{ old('name', $category->name) }}" 
                    required
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Deskripsi Kategori</label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="3" 
                    class="form-control @error('description') is-invalid @enderror"
                >{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    💾 Perbarui Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
