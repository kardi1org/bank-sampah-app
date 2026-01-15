{{-- Menu Items Partial --}}

<!-- Dashboard -->
<a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <i class="fas fa-chart-line"></i>
    <span class="sidebar-text">Dashboard</span>
</a>

<!-- Menu untuk Admin dan Petugas -->
@if (in_array(Auth::user()->role, ['admin', 'petugas']))
    @php
        $isInputSetoran = request()->routeIs('admin.input-setoran') || request()->routeIs('petugas.input-setoran');
        $isDataNasabah = request()->routeIs('admin.data-nasabah') || request()->routeIs('petugas.data-nasabah');
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
@endif

<!-- Menu khusus Admin -->
@if (Auth::user()->role == 'admin')
    <a href="{{ route('admin.laporan-keuangan') }}"
        class="menu-item {{ request()->routeIs('admin.laporan-keuangan') ? 'active' : '' }}">
        <i class="fas fa-file-invoice-dollar"></i>
        <span class="sidebar-text">Laporan Keuangan</span>
    </a>

    <a href="{{ route('admin.transaksi-history') }}"
        class="menu-item {{ request()->routeIs('admin.transaksi-history') ? 'active' : '' }}">
        <i class="fas fa-history"></i>
        <span class="sidebar-text">Riwayat Transaksi</span>
    </a>

    <a href="{{ route('admin.kategori-sampah') }}"
        class="menu-item {{ request()->routeIs('admin.kategori-sampah') ? 'active' : '' }}">
        <i class="fas fa-trash"></i>
        <span class="sidebar-text">Kategori Sampah</span>
    </a>

    <a href="{{ route('admin.petugas-management') }}"
        class="menu-item {{ request()->routeIs('admin.petugas-management') ? 'active' : '' }}">
        <i class="fas fa-user-tie"></i>
        <span class="sidebar-text">Kelola Petugas</span>
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
<a href="{{ route('notifikasi') }}" class="menu-item {{ request()->routeIs('notifikasi') ? 'active' : '' }}">
    <i class="fas fa-bell"></i>
    <span class="sidebar-text">Notifikasi</span>
</a>

<a href="{{ route('pengaturan') }}" class="menu-item {{ request()->routeIs('pengaturan') ? 'active' : '' }}">
    <i class="fas fa-cog"></i>
    <span class="sidebar-text">Pengaturan</span>
</a>
