<?php

namespace App\Http\Controllers;

use App\Models\Transaction; // Sesuaikan dengan nama model transaksi Anda
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PetugasController extends Controller
{
    public function index()
    {
        // Ambil tanggal hari ini
        $today = Carbon::today()->toDateString();

        // 1. Hitung Total Berat Hari Ini
        // Tambahkan prefix 'transactions.' pada created_at untuk menghindari ambigu
        $totalBerat = Transaction::join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->whereDate('transactions.created_at', $today)
            ->sum('transaction_details.weight');

        // 2. Hitung Total Transaksi Hari Ini
        $totalTransaksi = Transaction::whereDate('created_at', $today)->count();

        // 3. Hitung Total Nasabah Unik Hari Ini
        $totalNasabah = Transaction::whereDate('created_at', $today)
            ->distinct('user_id')
            ->count('user_id');

        // 4. Ambil 5 Transaksi Terbaru (Sebutkan transactions.* agar kolom id tidak tertukar)
        $recentTransactions = Transaction::with(['user', 'details'])
            ->select('transactions.*')
            ->whereDate('transactions.created_at', $today)
            ->latest('transactions.created_at')
            ->take(5)
            ->get();

        // Kirim data ke view
        return view('petugas.dashboard', compact(
            'totalBerat',
            'totalTransaksi',
            'totalNasabah',
            'recentTransactions'
        ));
    }
}
