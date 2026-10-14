@extends('layouts.auth')

@section('content')
<div class="auth-wrapper">
    <!-- Left Promotional Brand Side -->
    <div class="auth-side-brand">
        <div class="brand-top">
            <div style="display: flex; align-items: center; gap: 0.85rem;">
                <div class="brand-icon" style="width: 44px; height: 44px; font-size: 1.4rem;">V</div>
                <div>
                    <h2 style="font-size: 1.4rem; font-weight: 800; letter-spacing: -0.02em;">VOGUEORA</h2>
                    <p style="font-size: 0.78rem; color: #a5b4fc; text-transform: uppercase; letter-spacing: 0.1em;">Haute Couture & Apparel</p>
                </div>
            </div>
        </div>

        <div class="brand-middle">
            <h1 style="font-size: 2.2rem; font-weight: 800; line-height: 1.25; margin-bottom: 1rem;">
                Sistem Pengendalian &<br>Manajemen Inventaris
            </h1>
            <p style="color: #cbd5e1; font-size: 0.98rem; max-width: 440px; line-height: 1.6;">
                Integrasi penuh arsitektur Laravel Fundamental dengan pemisahan layer MVC: Request &rarr; Route &rarr; Controller &rarr; Model &rarr; Database &rarr; Response.
            </p>
        </div>

        <div class="brand-footer">
            <p style="font-size: 0.78rem; color: #94a3b8;">
                &copy; {{ date('Y') }} Vogueora Apparel Systems. Pertemuan 5 Laravel Integration.
            </p>
        </div>
    </div>

    <!-- Right Login Form Side -->
    <div class="auth-side-form">
        <div style="margin-bottom: 2rem;">
            <h2 style="font-size: 1.65rem; font-weight: 800; color: #0f172a; letter-spacing: -0.02em;">Masuk Akun</h2>
            <p style="font-size: 0.88rem; color: #64748b; margin-top: 0.25rem;">
                Silakan masukkan kredensial Anda untuk mengakses sistem inventaris.
            </p>
        </div>

        {{-- Flash Alerts --}}
        @if(session('info'))
            <x-alert type="info">
                {{ session('info') }}
            </x-alert>
        @endif

        @if(session('error'))
            <x-alert type="error">
                {{ session('error') }}
            </x-alert>
        @endif

        {{-- Slide 25: Form dengan @csrf --}}
        <form method="POST" action="{{ route('login.post') }}" id="login-form">
            @csrf

            <div class="form-group">
                <label for="username" class="form-label">Nama Pengguna (Username)</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    class="form-control @error('username') is-invalid @enderror" 
                    value="{{ old('username') }}" 
                    placeholder="Contoh: admin atau staff" 
                    required 
                    autofocus
                >
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Kata Sandi (Password)</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    placeholder="Masukkan kata sandi..." 
                    required
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.82rem; color: #475569; cursor: pointer;">
                    <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    Ingat Saya di Perangkat Ini
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem; font-size: 0.95rem;" id="btn-submit-login">
                Masuk ke Sistem Inventaris
            </button>
        </form>

        <!-- Quick Demo Fill Buttons -->
        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px dashed #e2e8f0;">
            <p style="font-size: 0.76rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.75rem;">
                ⚡ Tombol Isi Cepat Demo (Quick Fill):
            </p>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button type="button" class="quick-fill-btn" onclick="fillCredentials('admin', 'admin123')" id="btn-fill-admin">
                    👑 Admin (Wenny)
                </button>
                <button type="button" class="quick-fill-btn" onclick="fillCredentials('staff', 'staff123')" id="btn-fill-staff">
                    📦 Staff (Aditya)
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function fillCredentials(user, pass) {
        document.getElementById('username').value = user;
        document.getElementById('password').value = pass;
    }
</script>
@endsection
