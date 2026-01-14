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

    <div style="background: #4338ca; padding: 20px; text-align: center; border-radius: 18px 18px 0 0;">
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
                                style="display: flex; align-items: flex-start; background: #f9fafb; padding: 15px; border-radius: 15px; border: 1px solid #e5e7eb; margin-bottom: 10px; gap: 10px; position: relative; z-index: {{ 100 - $index }};">

                                <div style="flex-grow: 1; position: relative;" x-data="{ searchTerm: @entangle('searchCategory.' . $index) }">

                                    @if (!$item['is_gabrukan'])
                                        <input type="text" x-model="searchTerm"
                                            wire:model.live.debounce.300ms="searchCategory.{{ $index }}"
                                            placeholder="Ketik nama kategori..." {{-- Perubahan Warna Border Jika Error --}}
                                            style="width: 100%; padding: 10px; border-radius: 8px; font-weight: bold; color: black;
                                             border: 2px solid @error('listSampah.' . $index . '.category_id') #ef4444 @else {{ $item['category_id'] ? '#4338ca' : '#ccc' }} @enderror;
                                             background: {{ $item['category_id'] ? '#f0f4ff' : 'white' }};"
                                            {{ $item['category_id'] ? 'readonly' : '' }}>

                                        {{-- Pesan Error di bawah Jenis Sampah --}}
                                        @error('listSampah.' . $index . '.category_id')
                                            <span
                                                style="color: #ef4444; font-size: 10px; font-weight: bold; margin-top: 4px; display: block;">
                                                ⚠️ Jenis sampah wajib dipilih
                                            </span>
                                        @enderror

                                        @if (isset($categoryResults[$index]) && $categoryResults[$index]->count() > 0 && !$item['category_id'])
                                            <div
                                                style="position: absolute; width: 100%; background: white; border: 1px solid #4338ca; border-radius: 8px; margin-top: 5px; max-height: 200px; overflow-y: auto; box-shadow: 0 10px 15px rgba(0,0,0,0.2); z-index: 9999;">
                                                @foreach ($categoryResults[$index] as $cat)
                                                    <div wire:click="selectCategory({{ $index }}, {{ $cat->id }}, '{{ $cat->name }}')"
                                                        @click="searchTerm = '{{ $cat->name }}'"
                                                        style="padding: 12px; border-bottom: 1px solid #eee; cursor: pointer; color: black; font-weight: bold;"
                                                        onmouseover="this.style.backgroundColor='#e0e7ff'"
                                                        onmouseout="this.style.backgroundColor='white'">
                                                        🔍 {{ $cat->name }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @else
                                        <div
                                            style="color: #d97706; font-weight: bold; font-size: 13px; padding: 10px; background: #fffbeb; border: 1px solid #fef3c7; border-radius: 8px;">
                                            🚚 GABRUKAN
                                        </div>
                                    @endif
                                </div>

                                <div style="display: flex; flex-direction: column; align-items: flex-end;">
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        <input type="number" wire:model.live="listSampah.{{ $index }}.weight"
                                            step="0.01"
                                            style="width: 70px; padding: 10px; border: 2px solid @error('listSampah.' . $index . '.weight') #ef4444 @else #ccc @enderror; border-radius: 8px; text-align: center; color: black; font-weight: bold;"
                                            placeholder="0">
                                        <span style="font-weight: bold; color: black;">Kg</span>
                                        <button wire:click="hapusBaris({{ $index }})"
                                            style="color: #ef4444; background: none; border: none; font-size: 22px; cursor: pointer;">&times;</button>
                                    </div>

                                    @error('listSampah.' . $index . '.weight')
                                        <span style="color: #ef4444; font-size: 10px; font-weight: bold; margin-top: 4px;">
                                            ⚠️ Berat wajib isi > 0
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button wire:click="simpan"
                        style="width: 100%; background: #16a34a; color: white; padding: 18px; border: none; border-radius: 12px; font-size: 16px; font-weight: bold; cursor: pointer; margin-bottom: 30px;">SIMPAN
                        TRANSAKSI</button>
                @endif
            </div>
        @else
            <div
                style="text-align: center; padding: 40px; background: #fff1f2; border-radius: 15px; border: 1px dashed #fb7185; color: #be123c; font-weight: bold;">
                Pilih petugas di atas dahulu.</div>
        @endif

        <div style="margin-top: 20px;">
            <h3 style="margin-bottom: 15px; font-size: 15px; color: #1e293b; font-weight: bold;">Riwayat Hari Ini</h3>
            <div class="history-table" style="overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 12px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                    <tr style="background: #f1f5f9; color: #64748b; text-align: left;">
                        <th style="padding: 12px;">Waktu</th>
                        <th style="padding: 12px;">Nasabah</th>
                        <th style="padding: 12px;">Berat</th>
                        <th style="padding: 12px;">Petugas</th>
                    </tr>
                    @forelse($todayTransactions as $trx)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px;">{{ $trx->created_at->format('H:i') }}</td>
                            <td style="padding: 12px; font-weight: bold; color: #4338ca;">{{ $trx->user->name }}</td>
                            <td style="padding: 12px;">
                                @foreach ($trx->details as $detail)
                                    <span
                                        style="background: #f1f5f9; padding: 2px 5px; border-radius: 4px; display: inline-block; margin-bottom: 2px;">{{ $detail->category->name ?? 'Gab' }}:
                                        {{ $detail->weight }}Kg</span>
                                @endforeach
                            </td>
                            <td style="padding: 12px; font-size: 10px; color: #64748b;">
                                {{ $trx->incentives->pluck('officer.name')->implode(', ') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 20px; text-align: center; color: #94a3b8;">Belum ada
                                transaksi hari ini.</td>
                        </tr>
                    @endforelse
                </table>
            </div>
        </div>

    </div>
</div>
