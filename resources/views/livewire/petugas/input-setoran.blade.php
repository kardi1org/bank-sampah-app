<div
    style="width: 100%; background: white; border: 2px solid #e2e8f0; border-radius: 20px; font-family: sans-serif; box-sizing: border-box; position: relative;">

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-masuk {
            animation: fadeIn 0.5s ease forwards;
        }

        .history-table::-webkit-scrollbar {
            height: 6px;
        }

        .history-table::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>

    <div style="background: #4338ca; padding: 8px; text-align: center; border-radius: 18px 18px 0 0;">
        <h2 style="color: white; margin: 0; font-size: 20px; font-weight: bold;">Input Setoran Timbangan</h2>
    </div>

    <div style="padding: 25px; box-sizing: border-box;">
        @if ($errors->any())
            <div
                style="background: #fef2f2; border: 1px solid #fee2e2; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px; color: #b91c1c; font-size: 13px; font-weight: bold;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div>
            @if (session()->has('message'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                    style="position: fixed; top: 20px; right: 20px; background: #16a34a; color: white; padding: 15px 25px; border-radius: 12px; font-weight: bold; box-shadow: 0 10px 15px rgba(0,0,0,0.1); z-index: 10000;">
                    ✅ {{ session('message') }}
                </div>
            @endif

        </div>

        <div
            style="margin-bottom: 30px; padding: 20px; background: #f8fafc; border: 2px solid #cbd5e1; border-radius: 15px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <label style="font-weight: 900; color: #1e293b; font-size: 11px; text-transform: uppercase;">👥 Petugas
                    Bertugas:</label>

                @if (!$showAllOfficers && count($selectedOfficers) > 0)
                    <button wire:click="gantiPetugas"
                        style="background: none; border: none; color: #4338ca; font-size: 11px; font-weight: bold; cursor: pointer; text-decoration: underline;">
                        GANTI PETUGAS
                    </button>
                @endif
            </div>

            @if ($showAllOfficers || count($selectedOfficers) == 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 10px;">
                    @foreach ($allOfficers as $off)
                        <label
                            style="padding: 10px; border: 2px solid {{ in_array($off->id, $selectedOfficers) ? '#4338ca' : '#d1d5db' }}; border-radius: 10px; display: flex; align-items: center; cursor: pointer; color: black; font-weight: bold; font-size: 12px; background: {{ in_array($off->id, $selectedOfficers) ? '#e0e7ff' : 'white' }};">
                            <input type="checkbox" wire:model.live="selectedOfficers" value="{{ $off->id }}"
                                style="margin-right: 8px;">
                            {{ $off->name }}
                        </label>
                    @endforeach
                </div>
                @error('selectedOfficers')
                    <span style="color: #ef4444; font-size: 11px; font-weight: bold; margin-top: 5px; display: block;">⚠️
                        Pilih minimal 1 petugas</span>
                @enderror
            @else
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    @foreach ($allOfficers as $off)
                        @if (in_array($off->id, $selectedOfficers))
                            <div
                                style="padding: 8px 15px; background: #4338ca; color: white; border-radius: 20px; font-size: 12px; font-weight: bold; display: flex; align-items: center; gap: 5px;">
                                ✅ {{ $off->name }}
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        @if (count($selectedOfficers) > 0)
            <div class="animate-masuk">
                <div style="margin-bottom: 25px; position: relative; z-index: 1000;">
                    <label
                        style="display: block; font-weight: bold; color: #374151; margin-bottom: 8px; font-size: 14px;">Pilih
                        Nasabah:</label>

                    @if (!$nasabahId)
                        <div class="animate-masuk">
                            <input type="text" wire:model.live.debounce.300ms="searchNasabah"
                                style="width: 100%; padding: 15px; border: 2px solid #d1d5db; border-radius: 12px; box-sizing: border-box; color: black; font-weight: bold;"
                                placeholder="Ketik nama nasabah...">

                            @if (!empty($searchNasabah))
                                <div
                                    style="border: 1px solid #d1d5db; border-radius: 10px; margin-top: 5px; background: white; box-shadow: 0 10px 15px rgba(0,0,0,0.1); position: absolute; width: 100%; z-index: 1001; max-height: 200px; overflow-y: auto;">
                                    @foreach ($nasabahs as $n)
                                        <div wire:click="selectNasabah({{ $n->id }}, '{{ $n->name }}')"
                                            style="padding: 12px; border-bottom: 1px solid #eee; cursor: pointer; color: black; font-weight: bold;">
                                            {{ $n->name }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; background: #e0e7ff; padding: 15px; border-radius: 12px; border: 2px solid #4338ca;">
                            <div>
                                <span
                                    style="display: block; font-size: 11px; color: #4338ca; font-weight: bold; text-transform: uppercase;">Nasabah
                                    Terpilih:</span>
                                <span
                                    style="font-size: 18px; color: black; font-weight: 800;">{{ $namaNasabah }}</span>
                            </div>
                            <button wire:click="gantiNasabah"
                                style="background: #ef4444; color: white; border: none; padding: 8px 15px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 12px;">
                                GANTI NASABAH
                            </button>
                        </div>
                    @endif
                </div>

                @if ($nasabahId)
                    <div
                        style="background: #ffffff; border: 2px solid #4338ca; border-radius: 20px; padding: 20px; margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                            <span style="color: black; font-weight: bold;">Nasabah: {{ $namaNasabah }}</span>

                        </div>

                        <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                            <button wire:click="tambahBaris('pilah')"
                                style="flex: 1; background: #2563eb; color: white; padding: 12px; border: none; border-radius: 10px; font-weight: bold; cursor: pointer;">+
                                TERPILAH</button>
                            <button wire:click="tambahBaris('gabrukan')"
                                style="flex: 1; background: #f59e0b; color: black; padding: 12px; border: none; border-radius: 10px; font-weight: bold; cursor: pointer;">+
                                GABRUKAN</button>
                        </div>
                        @foreach ($listSampah as $index => $item)
                            <div wire:key="baris-{{ $index }}"
                                style="background: #f8fafc; padding: 15px; border-radius: 15px; border: 1px solid #e2e8f0; margin-bottom: 12px; position: relative; z-index: {{ 100 - $index }};">

                                <style>
                                    .input-wrapper {
                                        display: flex;
                                        gap: 12px;
                                        align-items: center;
                                        /* Sejajar secara vertikal */
                                    }

                                    .cat-box {
                                        flex-grow: 1;
                                        /* Di web: Kategori akan lebar mengikuti sisa ruang */
                                        position: relative;
                                    }

                                    .kg-box {
                                        flex: 0 0 130px;
                                        /* Lebar tetap untuk KG agar tidak terlalu lebar */
                                        display: flex;
                                        align-items: center;
                                        gap: 5px;
                                    }

                                    .action-box {
                                        flex: 0 0 40px;
                                        display: flex;
                                        justify-content: flex-end;
                                    }

                                    .input-field {
                                        width: 100%;
                                        padding: 10px;
                                        border-radius: 8px;
                                        font-weight: bold;
                                        color: black;
                                        box-sizing: border-box;
                                        font-size: 14px;
                                    }

                                    /* Tampilan Mobile */
                                    @media (max-width: 640px) {
                                        .input-wrapper {
                                            flex-wrap: wrap;
                                            gap: 8px;
                                        }

                                        .cat-box {
                                            flex: 0 0 100%;
                                            /* Baris penuh untuk kategori */
                                        }

                                        .kg-box {
                                            flex: 1;
                                            /* KG ambil sisa ruang di kiri tombol trash */
                                            justify-content: flex-start;
                                        }

                                        .action-box {
                                            flex: 0 0 45px;
                                        }
                                    }
                                </style>

                                <div class="input-wrapper">
                                    <div class="cat-box" x-data="{ searchTerm: @entangle('searchCategory.' . $index) }">
                                        @if (!$item['is_gabrukan'])
                                            <input type="text" x-model="searchTerm"
                                                wire:model.live.debounce.300ms="searchCategory.{{ $index }}"
                                                placeholder="Pilih Kategori Sampah..." class="input-field"
                                                style="border: 2px solid @error('listSampah.' . $index . '.category_id') #ef4444 @else {{ $item['category_id'] ? '#4338ca' : '#cbd5e1' }} @enderror;
                                               background: {{ $item['category_id'] ? '#f0f4ff' : 'white' }};"
                                                {{ $item['category_id'] ? 'readonly' : '' }}>

                                            @if (isset($categoryResults[$index]) && $categoryResults[$index]->count() > 0 && !$item['category_id'])
                                                <div
                                                    style="position: absolute; width: 100%; background: white; border: 2px solid #4338ca; border-radius: 8px; margin-top: 5px; max-height: 180px; overflow-y: auto; box-shadow: 0 10px 15px rgba(0,0,0,0.1); z-index: 9999;">
                                                    @foreach ($categoryResults[$index] as $cat)
                                                        <div wire:click="selectCategory({{ $index }}, {{ $cat->id }}, '{{ $cat->name }}')"
                                                            @click="searchTerm = '{{ $cat->name }}'"
                                                            style="padding: 12px; border-bottom: 1px solid #f1f5f9; cursor: pointer; color: black; font-weight: bold; font-size: 13px;"
                                                            onmouseover="this.style.backgroundColor='#e0e7ff'"
                                                            onmouseout="this.style.backgroundColor='white'">
                                                            📦 {{ $cat->name }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @else
                                            <div
                                                style="color: #d97706; font-weight: 800; font-size: 13px; padding: 10px; background: #fffbeb; border: 2px solid #fcd34d; border-radius: 8px;">
                                                🚚 GABRUKAN
                                            </div>
                                        @endif
                                    </div>

                                    <div class="kg-box">
                                        <input type="number" wire:model.live="listSampah.{{ $index }}.weight"
                                            step="0.01" class="input-field"
                                            style="border: 2px solid @error('listSampah.' . $index . '.weight') #ef4444 @else #cbd5e1 @enderror; text-align: center;"
                                            placeholder="0">
                                        <span style="font-weight: 900; color: #475569; font-size: 13px;">Kg</span>
                                    </div>

                                    <div class="action-box">
                                        <button wire:click="hapusBaris({{ $index }})"
                                            style="background: #fef2f2; color: #ef4444; border: 1px solid #fee2e2; width: 38px; height: 38px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 16px; transition: 0.2s;"
                                            onmouseover="this.style.background='#fee2e2'"
                                            onmouseout="this.style.background='#fef2f2'">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" y1="11" x2="10" y2="17" />
                                                <line x1="14" y1="11" x2="14" y2="17" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div style="display: flex; gap: 15px; margin-top: 5px;">
                                    @error('listSampah.' . $index . '.category_id')
                                        <span style="color: #ef4444; font-size: 10px; font-weight: bold;">⚠️ Kategori
                                            wajib</span>
                                    @enderror
                                    @error('listSampah.' . $index . '.weight')
                                        <span style="color: #ef4444; font-size: 10px; font-weight: bold;">⚠️ Berat
                                            wajib</span>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div style="display: flex; gap: 10px; margin-bottom: 30px;">
                        <button wire:click="simpan"
                            style="flex: 3; background: {{ $editingTransactionId ? '#2563eb' : '#16a34a' }}; color: white; padding: 18px; border: none; border-radius: 12px; font-size: 16px; font-weight: bold; cursor: pointer;">
                            {{ $editingTransactionId ? '💾 UPDATE TRANSAKSI' : '✅ SIMPAN TRANSAKSI' }}
                        </button>

                        @if ($editingTransactionId)
                            <button wire:click="batalEdit"
                                style="flex: 1; background: #64748b; color: white; padding: 18px; border: none; border-radius: 12px; font-size: 16px; font-weight: bold; cursor: pointer;">
                                ✖ BATAL
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        @else
            <div
                style="text-align: center; padding: 40px; background: #fff1f2; border-radius: 15px; border: 1px dashed #fb7185; color: #be123c; font-weight: bold;">
                Pilih petugas di atas dahulu.</div>
        @endif

        <div style="margin-top: 30px;">
            <h3
                style="margin-bottom: 15px; font-size: 16px; color: #1e293b; font-weight: bold; display: flex; align-items: center; gap: 8px;">
                🕒 Riwayat Transaksi Hari Ini
            </h3>

            <style>
                /* Sembunyikan salah satu berdasarkan ukuran layar */
                .desktop-history {
                    display: block;
                }

                .mobile-history {
                    display: none;
                }

                @media (max-width: 768px) {
                    .desktop-history {
                        display: none;
                    }

                    .mobile-history {
                        display: block;
                    }
                }

                .card-history {
                    background: #f8fafc;
                    border: 1px solid #e2e8f0;
                    border-radius: 15px;
                    padding: 15px;
                    margin-bottom: 12px;
                }

                .btn-action {
                    padding: 8px 12px;
                    border: none;
                    border-radius: 8px;
                    cursor: pointer;
                    font-weight: bold;
                    font-size: 12px;
                    display: flex;
                    align-items: center;
                    gap: 5px;
                }
            </style>

            <div class="desktop-history"
                style="overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 12px; background: white;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <thead>
                        <tr style="background: #f1f5f9; color: #64748b; text-align: left;">
                            <th style="padding: 12px;">Waktu</th>
                            <th style="padding: 12px;">Nasabah</th>
                            <th style="padding: 12px;">Detail Sampah</th>
                            <th style="padding: 12px;">Petugas</th>
                            <th style="padding: 12px;">Input Oleh</th>
                            <th style="padding: 12px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todayTransactions as $trx)
                            <tr style="border-bottom: 1px solid #f1f5f9;" wire:key="trx-dt-{{ $trx->id }}">
                                <td style="padding: 12px;">{{ $trx->created_at->format('H:i') }}</td>
                                <td style="padding: 12px; font-weight: bold; color: #4338ca;">{{ $trx->user->name }}
                                </td>
                                <td style="padding: 12px;">
                                    @foreach ($trx->details as $detail)
                                        <span
                                            style="background: #eef2ff; color: #4338ca; padding: 2px 6px; border-radius: 4px; font-size: 11px; margin-right: 4px; display: inline-block;">
                                            {{ $detail->category->name ?? 'Gab' }}: {{ $detail->weight }}kg
                                        </span>
                                    @endforeach
                                </td>
                                <td style="padding: 12px; font-size: 11px; color: #64748b;">
                                    {{ $trx->incentives->pluck('officer.name')->implode(', ') }}
                                </td>
                                <td style="padding: 12px; font-size: 11px;">
                                    <div style="font-weight: bold; color: #1e293b;">
                                        {{ $trx->creator->name ?? 'Sistem' }}</div>
                                    @if ($trx->updated_by)
                                        <div style="color: #ef4444; font-size: 9px; margin-top: 2px;">
                                            ✎ Edt: {{ $trx->editor->name }}
                                        </div>
                                    @endif
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <button wire:click="editTransaction({{ $trx->id }})"
                                            style="background: #2563eb; color: white; border: none; padding: 6px 10px; border-radius: 6px; cursor: pointer;">✏️</button>
                                        <button
                                            onclick="confirm('Yakin ingin menghapus transaksi ini?') || event.stopImmediatePropagation()"
                                            wire:click="hapusTransaksi({{ $trx->id }})"
                                            style="background: #ef4444; color: white; border: none; padding: 6px 10px; border-radius: 6px; cursor: pointer;">🗑️</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 30px; text-align: center; color: #94a3b8;">Belum
                                    ada transaksi hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mobile-history">
                @forelse($todayTransactions as $trx)
                    <div class="card-history" wire:key="trx-mb-{{ $trx->id }}">
                        <div
                            style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                            <div>
                                <span
                                    style="font-size: 10px; color: #64748b; font-weight: bold;">{{ $trx->created_at->format('H:i') }}
                                    WIB</span>
                                <div style="font-weight: 800; color: #4338ca; font-size: 15px;">{{ $trx->user->name }}
                                </div>
                            </div>
                            <div style="text-align: right; font-size: 10px; color: #94a3b8;">
                                ID: #{{ $trx->id }}
                            </div>
                        </div>

                        <div style="margin-bottom: 12px;">
                            @foreach ($trx->details as $detail)
                                <div
                                    style="font-size: 12px; color: #1e293b; background: white; padding: 4px 10px; border-radius: 6px; margin-bottom: 4px; border: 1px solid #e2e8f0;">
                                    <strong>{{ $detail->category->name ?? 'Gabrukan' }}</strong>:
                                    {{ $detail->weight }} Kg
                                </div>
                            @endforeach
                        </div>

                        <div style="font-size: 11px; color: #64748b; margin-bottom: 15px;">
                            👤 Petugas: {{ $trx->incentives->pluck('officer.name')->implode(', ') }}
                        </div>

                        <div
                            style="font-size: 11px; color: #64748b; margin-top: 8px; padding-top: 8px; border-top: 1px dashed #e2e8f0;">
                            <span>✍️ Input: {{ $trx->creator->name ?? '-' }}</span>
                            @if ($trx->updated_by)
                                <span style="color: #ef4444; margin-left: 10px;">✎ Edt:
                                    {{ $trx->editor->name }}</span>
                            @endif
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <button wire:click="editTransaction({{ $trx->id }})" class="btn-action"
                                style="background: #dbeafe; color: #1e40af; justify-content: center;">
                                ✏️ Edit Data
                            </button>
                            <button onclick="confirm('Hapus transaksi ini?') || event.stopImmediatePropagation()"
                                wire:click="hapusTransaksi({{ $trx->id }})" class="btn-action"
                                style="background: #fee2e2; color: #991b1b; justify-content: center;">
                                🗑️ Hapus
                            </button>
                        </div>
                    </div>
                @empty
                    <div
                        style="text-align: center; padding: 20px; color: #94a3b8; border: 2px dashed #e2e8f0; border-radius: 15px;">
                        Belum ada transaksi hari ini.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
