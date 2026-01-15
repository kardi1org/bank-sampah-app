@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Halo, {{ Auth::user()->name }}! 👋</h2>
                <p class="text-gray-500 text-sm">Ringkasan aktivitas bank sampah hari ini</p>
            </div>
            <a href="{{ route('petugas.input-setoran') }}"
                class="flex items-center bg-yellow-400 hover:bg-yellow-500 text-gray-900 px-4 py-2 rounded-lg font-bold transition-all duration-300 shadow-md hover:shadow-lg active:scale-95">
                <i class="fas fa-plus-circle mr-3"></i>
                <span>Input Setoran</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-5">
                    <div class="p-4 bg-blue-50 rounded-xl text-blue-600">
                        <i class="fas fa-weight-hanging fa-2x"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Total Berat</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $totalBerat }} <span
                                class="text-sm font-normal text-gray-400 ml-1">Kg</span></h3>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-5">
                    <div class="p-4 bg-green-50 rounded-xl text-green-600">
                        <i class="fas fa-exchange-alt fa-2x"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Total Transaksi</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $totalTransaksi }} <span
                                class="text-sm font-normal text-gray-400 ml-1">Sesi</span></h3>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-5">
                    <div class="p-4 bg-purple-50 rounded-xl text-purple-600">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Nasabah</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $totalNasabah }} <span
                                class="text-sm font-normal text-gray-400 ml-1">Orang</span></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 text-lg">Setoran Terakhir Hari Ini</h3>
                <a href="{{ route('petugas.riwayat-hari-ini') }}"
                    class="text-blue-600 text-sm font-semibold hover:underline">Lihat Semua</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-400 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Nasabah</th>
                            <th class="px-6 py-4">Berat</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentTransactions as $trx)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $trx->created_at->format('H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800 text-sm">{{ $trx->user->name }}</div>
                                    <div class="text-xs text-gray-400">ID: #{{ $trx->id }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $trx->details->sum('weight') }} Kg
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-gray-400 hover:text-blue-600 transition-colors">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">
                                    Belum ada transaksi hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
