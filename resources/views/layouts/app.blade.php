<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

    <style>
        /* Reset dan base styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            overflow-x: hidden;
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            line-height: 1.5;
        }

        /* Transisi sidebar */
        .sidebar-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Untuk mobile - sidebar fixed */
        .sidebar-mobile {
            position: fixed;
            left: -100%;
            top: 0;
            bottom: 0;
            z-index: 50;
            width: 260px;
            background-color: #1e293b;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-mobile.open {
            left: 0;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 40;
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Untuk desktop - sidebar collapsible */
        .sidebar-desktop {
            width: 16rem;
            flex-shrink: 0;
            background-color: #1e293b;
            display: none;
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
        }

        @media (min-width: 768px) {
            .sidebar-desktop {
                display: block;
            }
        }

        .sidebar-desktop.collapsed {
            width: 5rem;
        }

        /* Teks yang disembunyikan saat collapsed */
        .sidebar-desktop.collapsed .sidebar-text {
            opacity: 0;
            width: 0;
            height: 0;
            overflow: hidden;
        }

        .sidebar-desktop.collapsed .user-name,
        .sidebar-desktop.collapsed .user-role {
            display: none;
        }

        .sidebar-desktop.collapsed .sidebar-title {
            opacity: 0;
            width: 0;
            height: 0;
            overflow: hidden;
        }

        .sidebar-desktop.collapsed .user-info {
            justify-content: center;
            padding: 0.75rem 0.5rem;
        }

        .sidebar-desktop.collapsed .user-avatar {
            margin: 0;
        }

        .sidebar-desktop.collapsed .menu-item {
            justify-content: center;
            padding: 0.625rem;
        }

        .sidebar-desktop.collapsed .menu-item i {
            margin-right: 0;
            font-size: 1.125rem;
        }

        .sidebar-desktop.collapsed .menu-item span {
            display: none;
        }

        /* Efek hover menu */
        .menu-item {
            transition: all 0.2s ease;
            color: #cbd5e1;
            border-radius: 0.375rem;
            margin-bottom: 0.125rem;
            display: flex;
            align-items: center;
            padding: 0.625rem 0.875rem;
            text-decoration: none;
            white-space: nowrap;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .menu-item:hover {
            background-color: #334155;
            color: white;
        }

        .menu-item.active {
            background-color: #3b82f6;
            color: white;
            font-weight: 600;
        }

        .menu-item i {
            width: 1.25rem;
            text-align: center;
            margin-right: 0.625rem;
            font-size: 1rem;
            flex-shrink: 0;
        }

        /* Warna sidebar */
        .bg-slate-800 {
            background-color: #1e293b;
        }

        /* Header sidebar */
        .sidebar-header {
            border-bottom: 1px solid #334155;
            background-color: #1e293b;
            padding: 0.875rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-title {
            color: white;
            font-size: 1.125rem;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.3s ease;
        }

        /* User info */
        .user-info {
            display: flex;
            align-items: center;
            padding: 0.875rem;
            border-top: 1px solid #334155;
            transition: all 0.3s ease;
        }

        .user-avatar {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 50%;
            background-color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-right: 0.625rem;
            transition: all 0.3s ease;
        }

        .user-avatar i {
            color: white;
            font-size: 0.875rem;
        }

        .user-details {
            flex: 1;
            min-width: 0;
            transition: all 0.3s ease;
        }

        .user-name {
            color: white;
            font-weight: 500;
            font-size: 0.8125rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            color: #94a3b8;
            font-size: 0.6875rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Responsive adjustments */
        @media (max-width: 767px) {
            .sidebar-desktop {
                display: none !important;
            }

            .toggle-desktop-sidebar {
                display: none !important;
            }

            .toggle-mobile-sidebar {
                display: block !important;
            }
        }

        @media (min-width: 768px) {
            .sidebar-mobile {
                display: none !important;
            }

            .sidebar-overlay {
                display: none !important;
            }

            .toggle-mobile-sidebar {
                display: none !important;
            }

            .toggle-desktop-sidebar {
                display: block !important;
            }
        }

        /* Container utama */
        .app-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Main content area - FIXED */
        .main-content-area {
            flex: 1;
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* HEADER YANG LEBIH KECIL DAN RAPI */
        .main-header {
            position: sticky;
            top: 0;
            z-index: 30;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 0.75rem 1rem;
            min-height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        @media (min-width: 768px) {
            .main-header {
                padding: 0.875rem 1.5rem;
                min-height: 68px;
            }
        }

        /* Header left section */
        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
        }

        /* Header right section */
        .header-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        /* MOBILE HEADER RIGHT - LOGOUT ICON */
        .mobile-logout {
            display: flex;
            align-items: center;
        }

        @media (min-width: 768px) {
            .mobile-logout {
                display: none;
            }
        }

        .mobile-logout-button {
            background: none;
            border: none;
            color: #ef4444;
            padding: 0.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 1.125rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mobile-logout-button:hover {
            background-color: #fef2f2;
        }

        /* TOMBOL TOGGLE */
        .toggle-mobile-sidebar,
        .toggle-desktop-sidebar {
            background: none;
            border: none;
            cursor: pointer;
            color: #4b5563;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 40px;
            height: 40px;
        }

        .toggle-mobile-sidebar:hover,
        .toggle-desktop-sidebar:hover {
            background-color: #f3f4f6;
            color: #111827;
        }

        .toggle-mobile-sidebar i,
        .toggle-desktop-sidebar i {
            font-size: 1.25rem;
        }

        /* JARAK ANTARA TOGGLE DAN JUDUL PANEL */
        .panel-title-container {
            display: flex;
            align-items: center;
            margin-left: 0.75rem;
            overflow: hidden;
        }

        .panel-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (min-width: 768px) {
            .panel-title {
                font-size: 1.25rem;
            }

            .panel-title-container {
                margin-left: 1rem;
            }
        }

        /* NAMA USER - DESKTOP ONLY */
        .desktop-user-display {
            display: none;
        }

        @media (min-width: 768px) {
            .desktop-user-display {
                display: flex;
                align-items: center;
                gap: 0.625rem;
                padding: 0.5rem 0.875rem;
                background-color: #f8fafc;
                border-radius: 0.5rem;
                border: 1px solid #e5e7eb;
            }
        }

        .desktop-user-display .user-name {
            font-weight: 600;
            color: #374151;
            font-size: 0.8125rem;
            white-space: nowrap;
        }

        .desktop-user-display .user-avatar-small {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #3b82f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        /* TOMBOL KELUAR - DESKTOP */
        .desktop-logout-button {
            display: none;
        }

        @media (min-width: 768px) {
            .desktop-logout-button {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                background-color: #ef4444;
                color: white;
                border: none;
                border-radius: 0.5rem;
                font-weight: 600;
                font-size: 0.8125rem;
                cursor: pointer;
                transition: all 0.2s ease;
                text-decoration: none;
                box-shadow: 0 1px 2px rgba(239, 68, 68, 0.2);
                flex-shrink: 0;
            }
        }

        .desktop-logout-button:hover {
            background-color: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
        }

        .desktop-logout-button:active {
            transform: translateY(0);
        }

        .desktop-logout-button i {
            font-size: 0.875rem;
        }

        /* Content utama */
        .main-content {
            flex: 1;
            width: 100%;
            padding: 1rem;
            background-color: #f9fafb;
        }

        @media (min-width: 768px) {
            .main-content {
                padding: 1.5rem;
            }
        }

        /* Atur lebar konten agar tidak terlalu lebar */
        .content-container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        /* Utility untuk responsive text */
        .text-sm {
            font-size: 0.875rem;
        }

        .text-base {
            font-size: 1rem;
        }

        .font-medium {
            font-weight: 500;
        }

        .font-semibold {
            font-weight: 600;
        }

        .font-bold {
            font-weight: 700;
        }

        .text-gray-600 {
            color: #4b5563;
        }

        .text-gray-800 {
            color: #1f2937;
        }

        .uppercase {
            text-transform: uppercase;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100">
    <!-- Overlay untuk mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="app-container">
        <!-- Sidebar untuk Mobile -->
        <aside id="sidebarMobile" class="sidebar-mobile sidebar-transition">
            <!-- Header Sidebar Mobile -->
            <div class="sidebar-header">
                <div class="sidebar-title">BANK SAMPAH</div>
                <button id="closeSidebar" class="text-white text-2xl hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Menu Navigation Mobile -->
            <nav class="p-4 space-y-1">
                <!-- Dashboard -->
                <!-- Dashboard sesuai role -->
                <a href="{{ Auth::user()->role == 'admin'
                    ? route('admin.dashboard')
                    : (Auth::user()->role == 'petugas'
                        ? route('petugas.dashboard')
                        : route('nasabah.dashboard')) }}"
                    class="menu-item {{ request()->routeIs('admin.dashboard') ||
                    request()->routeIs('petugas.dashboard') ||
                    request()->routeIs('nasabah.dashboard')
                        ? 'active'
                        : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>

                <!-- Menu untuk Admin dan Petugas -->
                @if (in_array(Auth::user()->role, ['admin', 'petugas']))
                    @php
                        $isInputSetoran =
                            request()->routeIs('admin.input-setoran') || request()->routeIs('petugas.input-setoran');
                        $isDataNasabah =
                            request()->routeIs('admin.data-nasabah') || request()->routeIs('petugas.data-nasabah');
                        $isRiwayat =
                            request()->routeIs('admin.riwayat-hari-ini') ||
                            request()->routeIs('petugas.riwayat-hari-ini');
                    @endphp

                    <a href="{{ Auth::user()->role == 'admin' ? route('admin.input-setoran') : route('petugas.input-setoran') }}"
                        class="menu-item {{ $isInputSetoran ? 'active' : '' }}">
                        <i class="fas fa-weight-scale"></i>
                        <span class="sidebar-text">Input Setoran</span>
                    </a>

                    <a href="{{ Auth::user()->role == 'admin' ? route('admin.data-nasabah') : route('petugas.data-nasabah') }}"
                        class="menu-item {{ $isDataNasabah ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span class="sidebar-text">Data Nasabah</span>
                    </a>

                    <a href="{{ Auth::user()->role == 'admin' ? route('admin.riwayat-hari-ini') : route('petugas.riwayat-hari-ini') }}"
                        class="menu-item {{ $isRiwayat ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        <span class="sidebar-text">Riwayat Hari Ini</span>
                    </a>
                @endif

                <!-- Menu khusus Admin -->
                @if (Auth::user()->role == 'admin')
                    <a href="{{ route('admin.laporan-keuangan') }}"
                        class="menu-item {{ request()->routeIs('admin.laporan-keuangan') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span class="sidebar-text">Laporan Keuangan</span>
                    </a>

                    <a href="{{ route('admin.kategori-sampah') }}"
                        class="menu-item {{ request()->routeIs('admin.kategori-sampah') ? 'active' : '' }}">
                        <i class="fas fa-trash"></i>
                        <span class="sidebar-text">Kategori Sampah</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                        class="menu-item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i>
                        <span class="sidebar-text">Kelola Pengguna</span>
                    </a>
                @endif

                <!-- Menu khusus Petugas -->
                @if (Auth::user()->role == 'petugas')
                    <a href="{{ route('petugas.riwayat-hari-ini') }}"
                        class="menu-item {{ request()->routeIs('petugas.riwayat-hari-ini') ? 'active' : '' }}">
                        <i class="fas fa-clock-rotate-left"></i>
                        <span class="sidebar-text">Riwayat Hari Ini</span>
                    </a>
                @endif

                <!-- Menu khusus Nasabah -->
                @if (Auth::user()->role == 'nasabah')
                    <a href="{{ route('nasabah.riwayat-setoran') }}"
                        class="menu-item {{ request()->routeIs('nasabah.riwayat-setoran') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        <span class="sidebar-text">Riwayat Setoran</span>
                    </a>

                    <a href="{{ route('nasabah.saldo') }}"
                        class="menu-item {{ request()->routeIs('nasabah.saldo') ? 'active' : '' }}">
                        <i class="fas fa-wallet"></i>
                        <span class="sidebar-text">Saldo Saya</span>
                    </a>
                @endif

                <!-- Menu untuk semua role -->
                <a href="{{ route('notifikasi') }}"
                    class="menu-item {{ request()->routeIs('notifikasi') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span class="sidebar-text">Notifikasi</span>
                </a>

                <a href="{{ route('pengaturan') }}"
                    class="menu-item {{ request()->routeIs('pengaturan') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span class="sidebar-text">Pengaturan</span>
                </a>
            </nav>

            <!-- Info User di Sidebar Mobile -->
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-details">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">{{ Auth::user()->role }}</div>
                </div>
            </div>
        </aside>

        <!-- Sidebar untuk Desktop -->
        <aside id="sidebarDesktop" class="sidebar-desktop sidebar-transition">
            <!-- Header Sidebar Desktop -->
            <div class="sidebar-header">
                <div class="sidebar-title">BANK SAMPAH</div>
            </div>

            <!-- Menu Navigation Desktop -->
            <nav class="p-4 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                    class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>

                <!-- Menu untuk Admin dan Petugas -->
                @if (in_array(Auth::user()->role, ['admin', 'petugas']))
                    @php
                        $isInputSetoran =
                            request()->routeIs('admin.input-setoran') || request()->routeIs('petugas.input-setoran');
                        $isDataNasabah =
                            request()->routeIs('admin.data-nasabah') || request()->routeIs('petugas.data-nasabah');
                        $isRiwayat =
                            request()->routeIs('admin.riwayat-hari-ini') ||
                            request()->routeIs('petugas.riwayat-hari-ini');
                    @endphp

                    <a href="{{ Auth::user()->role == 'admin' ? route('admin.input-setoran') : route('petugas.input-setoran') }}"
                        class="menu-item {{ $isInputSetoran ? 'active' : '' }}">
                        <i class="fas fa-weight-scale"></i>
                        <span class="sidebar-text">Input Setoran</span>
                    </a>

                    <a href="{{ Auth::user()->role == 'admin' ? route('admin.data-nasabah') : route('petugas.data-nasabah') }}"
                        class="menu-item {{ $isDataNasabah ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span class="sidebar-text">Data Nasabah</span>
                    </a>

                    <a href="{{ Auth::user()->role == 'admin' ? route('admin.riwayat-hari-ini') : route('petugas.riwayat-hari-ini') }}"
                        class="menu-item {{ $isRiwayat ? 'active' : '' }}">
                        <i class="fas fa-clock-rotate-left"></i>
                        <span class="sidebar-text">Riwayat Hari Ini</span>
                    </a>
                @endif

                <!-- Menu khusus Admin -->
                @if (Auth::user()->role == 'admin')
                    <a href="{{ route('admin.laporan-keuangan') }}"
                        class="menu-item {{ request()->routeIs('admin.laporan-keuangan') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span class="sidebar-text">Laporan Keuangan</span>
                    </a>

                    <a href="{{ route('admin.kategori-sampah') }}"
                        class="menu-item {{ request()->routeIs('admin.kategori-sampah') ? 'active' : '' }}">
                        <i class="fas fa-trash"></i>
                        <span class="sidebar-text">Kategori Sampah</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                        class="menu-item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i>
                        <span class="sidebar-text">Kelola Pengguna</span>
                    </a>
                @endif

                <!-- Menu khusus Nasabah -->
                @if (Auth::user()->role == 'nasabah')
                    <a href="{{ route('nasabah.riwayat-setoran') }}"
                        class="menu-item {{ request()->routeIs('nasabah.riwayat-setoran') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        <span class="sidebar-text">Riwayat Setoran</span>
                    </a>

                    <a href="{{ route('nasabah.saldo') }}"
                        class="menu-item {{ request()->routeIs('nasabah.saldo') ? 'active' : '' }}">
                        <i class="fas fa-wallet"></i>
                        <span class="sidebar-text">Saldo Saya</span>
                    </a>
                @endif

                <!-- Menu untuk semua role -->
                <a href="{{ route('notifikasi') }}"
                    class="menu-item {{ request()->routeIs('notifikasi') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span class="sidebar-text">Notifikasi</span>
                </a>

                <a href="{{ route('pengaturan') }}"
                    class="menu-item {{ request()->routeIs('pengaturan') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span class="sidebar-text">Pengaturan</span>
                </a>
            </nav>

            <!-- Info User di Sidebar Desktop -->
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-details">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">{{ Auth::user()->role }}</div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="main-content-area">
            <!-- Header yang lebih kecil dan responsive -->
            <header class="main-header">
                <div class="header-left">
                    <!-- Tombol Toggle Menu untuk Mobile -->
                    <button id="toggleMobileSidebar" class="toggle-mobile-sidebar">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Tombol Toggle Menu untuk Desktop -->
                    <button id="toggleDesktopSidebar" class="toggle-desktop-sidebar">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Container untuk judul panel -->
                    <div class="panel-title-container">
                        <h1 class="panel-title font-bold text-gray-800 uppercase">
                            {{ ucfirst(Auth::user()->role) }} PANEL
                        </h1>
                    </div>
                </div>

                <div class="header-right">
                    <!-- Nama User - Desktop only -->
                    <div class="desktop-user-display">
                        <div class="user-avatar-small">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="user-name">
                            {{ Auth::user()->name }}
                        </div>
                    </div>

                    <!-- Logout Icon - Mobile -->
                    <div class="mobile-logout">
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="mobile-logout-button" title="Keluar">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Tombol Keluar - Desktop -->
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="desktop-logout-button">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>KELUAR</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main Content -->
            <main class="main-content">
                <div class="content-container">
                    @hasSection('content')
                        @yield('content')
                    @else
                        {{ $slot ?? '' }}
                    @endif
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarMobile = document.getElementById('sidebarMobile');
            const sidebarDesktop = document.getElementById('sidebarDesktop');
            const toggleMobileSidebar = document.getElementById('toggleMobileSidebar');
            const toggleDesktopSidebar = document.getElementById('toggleDesktopSidebar');
            const closeSidebar = document.getElementById('closeSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            // State untuk desktop sidebar
            let isDesktopSidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

            // Inisialisasi desktop sidebar berdasarkan state yang tersimpan
            if (isDesktopSidebarCollapsed && window.innerWidth >= 768) {
                sidebarDesktop.classList.add('collapsed');
            }

            // Fungsi untuk toggle mobile sidebar
            function toggleMobileSidebarFunc() {
                sidebarMobile.classList.toggle('open');
                overlay.classList.toggle('active');
            }

            // Fungsi untuk close mobile sidebar
            function closeMobileSidebar() {
                sidebarMobile.classList.remove('open');
                overlay.classList.remove('active');
            }

            // Fungsi untuk toggle desktop sidebar (collapse/expand)
            function toggleDesktopSidebarFunc() {
                if (window.innerWidth >= 768) {
                    sidebarDesktop.classList.toggle('collapsed');
                    isDesktopSidebarCollapsed = sidebarDesktop.classList.contains('collapsed');
                    localStorage.setItem('sidebarCollapsed', isDesktopSidebarCollapsed);
                }
            }

            // Event listeners
            toggleMobileSidebar.addEventListener('click', toggleMobileSidebarFunc);
            toggleDesktopSidebar.addEventListener('click', toggleDesktopSidebarFunc);

            closeSidebar.addEventListener('click', closeMobileSidebar);
            overlay.addEventListener('click', closeMobileSidebar);

            // Close sidebar mobile saat klik link di menu
            document.querySelectorAll('.menu-item').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        closeMobileSidebar();
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                // Tutup sidebar mobile saat resize ke desktop
                if (window.innerWidth >= 768) {
                    closeMobileSidebar();
                }
            });
        });
    </script>

    @livewireScripts
</body>

</html>
