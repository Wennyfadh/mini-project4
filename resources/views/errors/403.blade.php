<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Akses Ditolak | Vogueora Apparel</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            padding: 2rem;
            text-align: center;
        }
        .error-card {
            background: #fff;
            padding: 3rem;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            max-width: 500px;
        }
        .error-code {
            font-size: 5rem;
            font-weight: 800;
            color: #e11d48;
            line-height: 1;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="error-page">
        <div class="error-card">
            <div class="error-code">403</div>
            <h1 style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;">
                Akses Ditolak (HTTP 403 Forbidden)
            </h1>
            <p style="color: #64748b; font-size: 0.92rem; line-height: 1.6; margin-bottom: 2rem;">
                {{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses atau otorisasi administrator untuk membuka halaman manajemen ini.' }}
            </p>
            <div style="display: flex; gap: 0.75rem; justify-content: center;">
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    &larr; Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</body>
</html>
