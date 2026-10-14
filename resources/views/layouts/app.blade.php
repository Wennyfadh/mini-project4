<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Vogueora Apparel - Sistem Manajemen Inventaris dan Pengendalian Stok Busana Terintegrasi Berbasis Laravel.">
    <title>@yield('title', 'Sistem Inventaris') | Vogueora Apparel</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Application Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
    <!-- Chart.js for Dashboard Visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @stack('styles')
</head>
<body>
    <div class="app-container">
        <!-- Sidebar Navigation (Slide 11 & 22) -->
        <aside class="app-sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon">V</div>
                <div class="brand-details">
                    <span class="brand-name">VOGUEORA</span>
                    <span class="brand-sub">Apparel Inventory</span>
                </div>
            </div>

            <ul class="sidebar-menu">
                <li class="menu-label">Menu Utama</li>
                <li>
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" id="nav-dashboard">
                        <span class="nav-link-icon">📊</span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" id="nav-products">
                        <span class="nav-link-icon">👗</span>
                        <span>Katalog Produk</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" id="nav-categories">
                        <span class="nav-link-icon">🏷️</span>
                        <span>Kategori Busana</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" id="nav-suppliers">
                        <span class="nav-link-icon">🏭</span>
                        <span>Pemasok (Suppliers)</span>
                    </a>
                </li>

                <li class="menu-label">Transaksi & Mutasi Stok</li>
                <li>
                    <a href="{{ route('stock.index') }}" class="nav-link {{ request()->routeIs('stock.index') ? 'active' : '' }}" id="nav-stock-index">
                        <span class="nav-link-icon">📋</span>
                        <span>Histori Mutasi Stok</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('stock.in') }}" class="nav-link {{ request()->routeIs('stock.in') ? 'active' : '' }}" id="nav-stock-in">
                        <span class="nav-link-icon">📥</span>
                        <span>Barang Masuk (In)</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('stock.out') }}" class="nav-link {{ request()->routeIs('stock.out') ? 'active' : '' }}" id="nav-stock-out">
                        <span class="nav-link-icon">📤</span>
                        <span>Barang Keluar (Out)</span>
                    </a>
                </li>

                <li class="menu-label">Laporan Bisnis</li>
                <li>
                    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" id="nav-reports">
                        <span class="nav-link-icon">📈</span>
                        <span>Laporan Inventaris</span>
                    </a>
                </li>

                @if(Auth::check() && Auth::user()->isAdmin())
                <li class="menu-label">Administrasi Sistem</li>
                <li>
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" id="nav-users">
                        <span class="nav-link-icon">👥</span>
                        <span>Manajemen Pengguna</span>
                    </a>
                </li>
                @endif
            </ul>

            <!-- Sidebar User Profile Footer -->
            @auth
            <div class="sidebar-user">
                <div class="user-info-box">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->displayName, 0, 1)) }}
                    </div>
                    <div class="user-text">
                        <span class="user-name" title="{{ Auth::user()->displayName }}">{{ Auth::user()->displayName }}</span>
                        <span class="user-role-badge role-{{ Auth::user()->role }}">
                            {{ Auth::user()->isAdmin() ? 'Administrator' : 'Staff Gudang' }}
                        </span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-logout" title="Keluar dari sistem" id="btn-logout">
                        🚪
                    </button>
                </form>
            </div>
            @endauth
        </aside>

        <!-- Main Workspace -->
        <div class="app-main">
            <!-- Top Navbar -->
            <header class="top-navbar">
                <div class="page-breadcrumb">
                    Vogueora Apparel &nbsp;/&nbsp; <strong>@yield('breadcrumb', 'Dashboard')</strong>
                </div>

                <div class="top-actions">
                    <a href="{{ route('stock.in') }}" class="btn btn-sm btn-success" id="btn-quick-in">
                        + Masuk
                    </a>
                    <a href="{{ route('stock.out') }}" class="btn btn-sm btn-danger" id="btn-quick-out">
                        - Keluar
                    </a>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="main-content">
                {{-- Flash Notifications using Blade Component (Slide 23) --}}
                @if(session('success'))
                    <x-alert type="success">
                        {{ session('success') }}
                    </x-alert>
                @endif

                @if(session('error'))
                    <x-alert type="error">
                        {{ session('error') }}
                    </x-alert>
                @endif

                @if(session('warning'))
                    <x-alert type="warning">
                        {{ session('warning') }}
                    </x-alert>
                @endif

                @if(session('info'))
                    <x-alert type="info">
                        {{ session('info') }}
                    </x-alert>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
