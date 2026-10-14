<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Autentikasi Pengguna Vogueora Apparel Inventory System.">
    <title>Masuk | Vogueora Apparel Inventory</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        .auth-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .auth-side-brand {
            flex: 1;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3.5rem;
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }
        .auth-side-brand::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, transparent 70%);
            border-radius: 50%;
        }
        .auth-side-form {
            width: 480px;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3.5rem;
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.05);
        }
        @media (max-width: 900px) {
            .auth-side-brand { display: none; }
            .auth-side-form { width: 100%; padding: 2rem; }
        }
        .quick-fill-btn {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            color: #334155;
        }
        .quick-fill-btn:hover {
            background-color: #e2e8f0;
            border-color: #94a3b8;
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
