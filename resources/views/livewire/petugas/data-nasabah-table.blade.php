<div style="width: 100%; font-family: sans-serif; box-sizing: border-box;">
    <style>
        .header-section {
            background: #4338ca;
            padding: 10px;
            text-align: center;
            border-radius: 20px 20px 0 0;
        }

        .content-section {
            padding: 20px;
            background: white;
            border: 2px solid #e2e8f0;
            border-top: none;
            border-radius: 0 0 20px 20px;
        }

        /* Desktop Table */
        .desktop-view {
            display: block;
        }

        .mobile-view {
            display: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f1f5f9;
            color: #64748b;
            text-align: left;
            padding: 12px;
            font-size: 11px;
            text-transform: uppercase;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Mobile Card Style */
        @media (max-width: 1023px) {
            .desktop-view {
                display: none;
            }

            .mobile-view {
                display: block;
            }

            .nasabah-card {
                background: white;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                padding: 15px;
                margin-bottom: 12px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            }
        }

        .btn-lihat {
            background: #4338ca;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 12px;
            width: 100%;
        }

        .badge-role {
            font-size: 9px;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>

    <div class="header-section">
        <h2 style="color: white; margin: 0; font-size: 18px; font-weight: bold;">Data Master Nasabah</h2>
    </div>

    <div class="content-section">
        <div style="margin-bottom: 20px;">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau email..."
                style="padding: 12px 15px; border: 2px solid #d1d5db; border-radius: 10px; width: 100%; box-sizing: border-box; outline: none;">
        </div>

        <div class="desktop-view">
            <div style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                <table>
                    <thead>
                        <tr>
                            <th>Nasabah</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nasabahs as $nasabah)
                            {{-- Tambahkan wire:key di sini agar Livewire bisa melacak baris ini --}}
                            <tr wire:key="nasabah-desktop-{{ $nasabah->id }}">
                                <td style="font-weight: bold; color: #1e293b;">{{ $nasabah->name }}</td>
                                <td style="color: #64748b; font-size: 13px;">{{ $nasabah->email }}</td>
                                <td>
                                    <span class="badge-role"
                                        style="background: #e0e7ff; color: #4338ca;">{{ $nasabah->role }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <button wire:click="lihatTransaksi({{ $nasabah->id }})" class="btn-lihat"
                                        style="width: auto;">
                                        Lihat Transaksi
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 20px; color: #94a3b8;">Data tidak
                                    ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mobile-view">
            @forelse($nasabahs as $nasabah)
                {{-- Tambahkan wire:key di sini untuk setiap kartu --}}
                <div class="nasabah-card" wire:key="nasabah-mobile-{{ $nasabah->id }}">
                    <div
                        style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                        <div>
                            <div style="font-weight: bold; font-size: 15px; color: #1e293b;">{{ $nasabah->name }}</div>
                            <div style="color: #64748b; font-size: 12px;">{{ $nasabah->email }}</div>
                        </div>
                        <span class="badge-role"
                            style="background: #e0e7ff; color: #4338ca;">{{ $nasabah->role }}</span>
                    </div>
                    <button wire:click="lihatTransaksi({{ $nasabah->id }})" class="btn-lihat">
                        Lihat Transaksi
                    </button>
                </div>
            @empty
                <div style="text-align: center; padding: 20px; color: #94a3b8;">Data tidak ditemukan.</div>
            @endforelse
        </div>

        <div style="margin-top: 20px;">
            {{ $nasabahs->links() }}
        </div>
    </div>
</div>
