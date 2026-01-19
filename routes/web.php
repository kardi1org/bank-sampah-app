<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Route untuk semua yang sudah login (tanpa verifikasi email)
Route::middleware(['auth'])->group(function () {
    // Profile routes (untuk semua role)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ========== ROUTE DASHBOARD BERDASARKAN ROLE ==========
    // Route utama dashboard yang akan redirect berdasarkan role
    Route::get('/dashboard', function () {
        $user = auth()->user();

        // Redirect berdasarkan role
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'petugas':
                return redirect()->route('petugas.dashboard');
            case 'nasabah':
                return redirect()->route('nasabah.dashboard');
            default:
                return redirect()->route('login');
        }
    })->name('dashboard');

    // ========== RUTE KHUSUS ADMIN ==========
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard Admin
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Input Setoran (Menggunakan view/wrapper yang sama dengan petugas)
        Route::get('/input-setoran', function () {
            return view('petugas.input-setoran');
        })->name('input-setoran');

        // Riwayat Hari Ini
        Route::get('/riwayat-hari-ini/{user_id?}', function ($user_id = null) {
            return view('petugas.riwayat-hari-ini', ['user_id' => $user_id]);
        })->name('riwayat-hari-ini');

        // Data Nasabah
        Route::get('/data-nasabah', function () {
            return view('petugas.data-nasabah');
        })->name('data-nasabah');

        // Laporan Keuangan
        Route::get('/laporan-keuangan', function () {
            return view('admin.laporan-keuangan');
        })->name('laporan-keuangan');

        // Kategori Sampah
        Route::get('/kategori-sampah', \App\Livewire\Admin\KategoriSampah::class)->name('kategori-sampah');

        // Transaksi History
        Route::get('/transaksi-history', function () {
            return view('petugas.transaksi-history');
        })->name('transaksi-history');

        // Kelola Petugas
        Route::get('/petugas-management', function () {
            return view('admin.petugas-management');
        })->name('petugas-management');
    });

    // ========== RUTE KHUSUS PETUGAS ==========
    Route::middleware(['role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
        // Dashboard Petugas
        // Route::get('/dashboard', function () {
        //     return view('petugas.dashboard');
        // })->name('dashboard');
        Route::get('/dashboard', [PetugasController::class, 'index'])->name('dashboard');
        // Input Setoran (Livewire Component)
        Route::get('/input-setoran', function () {
            return view('petugas.input-setoran'); // Wrapper untuk Livewire
        })->name('input-setoran');

        // Riwayat Hari Ini
        // Pastikan route riwayat menerima parameter user_id secara opsional {user_id?}
        Route::get('/riwayat-hari-ini/{user_id?}', function ($user_id = null) {
            return view('petugas.riwayat-hari-ini', ['user_id' => $user_id]);
        })->name('riwayat-hari-ini');

        // Data Nasabah (View khusus petugas)
        Route::get('/data-nasabah', function () {
            return view('petugas.data-nasabah');
        })->name('data-nasabah');
    });

    // ========== RUTE KHUSUS NASABAH ==========
    Route::middleware(['role:nasabah'])->prefix('nasabah')->name('nasabah.')->group(function () {
        // Dashboard Nasabah
        Route::get('/dashboard', function () {
            return view('nasabah.dashboard');
        })->name('dashboard');

        // Riwayat Setoran
        Route::get('/riwayat-setoran', function () {
            return view('nasabah.riwayat-setoran');
        })->name('riwayat-setoran');

        // Saldo
        Route::get('/saldo', function () {
            return view('nasabah.saldo');
        })->name('saldo');
    });

    // ========== RUTE UNTUK SEMUA ROLE ==========
    // Route yang bisa diakses admin, petugas, dan nasabah
    Route::middleware(['role:admin,petugas,nasabah'])->group(function () {
        Route::get('/notifikasi', function () {
            return view('notifikasi');
        })->name('notifikasi');

        Route::get('/pengaturan', function () {
            return view('pengaturan');
        })->name('pengaturan');
    });
});

require __DIR__ . '/auth.php';
