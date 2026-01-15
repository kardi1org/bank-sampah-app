<div>
    @if ($targetUser)
        <div style="background: #e0e7ff; padding: 15px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #4338ca; display: flex; justify-content: space-between; align-items: center;"
            class="animate-masuk">
            <div>
                <p style="margin: 0; font-size: 11px; color: #4338ca; font-weight: bold; text-transform: uppercase;">
                    Menampilkan Riwayat:</p>
                <h3 style="margin: 0; color: #1e293b; font-size: 16px;">{{ $targetUser->name }}</h3>
            </div>
            <a href="{{ route('petugas.riwayat-hari-ini') }}"
                style="text-decoration: none; background: white; color: #4338ca; padding: 8px 16px; border-radius: 8px; font-size: 11px; font-weight: bold; border: 1px solid #4338ca; transition: 0.3s;">
                LIHAT SEMUA HARI INI
            </a>
        </div>
    @endif

    <div style="margin-top: 10px;">
        <h3 style="margin-bottom: 15px; font-size: 15px; color: #1e293b; font-weight: bold;">
            @if ($targetUser)
                Semua Riwayat Transaksi Nasabah
            @else
                Riwayat Transaksi Masuk Hari Ini
            @endif
        </h3>

        <div class="history-table"
            style="overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 12px; background: white;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <thead style="background: #f8fafc;">
                    <tr>
                        <th style="padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0;">WAKTU</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0;">NASABAH</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0;">TOTAL</th>
                        <th style="padding: 12px; text-align: center; border-bottom: 1px solid #e2e8f0;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($todayTransactions as $item)
                        <tr>
                            <td style="padding: 12px; border-bottom: 1px solid #f1f5f9;">
                                {{ $item->created_at->format('H:i') }}
                                <div style="font-size: 10px; color: #94a3b8;">{{ $item->created_at->format('d/m/Y') }}
                                </div>
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #f1f5f9; font-weight: 600;">
                                {{ $item->user->name }}
                            </td>
                            <td
                                style="padding: 12px; border-bottom: 1px solid #f1f5f9; color: #16a34a; font-weight: bold;">
                                Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #f1f5f9; text-align: center;">
                                <button
                                    style="background: none; border: 1px solid #e2e8f0; padding: 5px 10px; border-radius: 6px; cursor: pointer; font-size: 11px;">Detail</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 40px; text-align: center; color: #94a3b8;">
                                Tidak ada transaksi ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 20px;">
            {{ $todayTransactions->links() }}
        </div>

    </div>
</div>
</div>
</div>
</div>
