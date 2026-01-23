<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-md-4 mb-4">
            <div style="background: white; padding: 25px; border-radius: 15px; border: 1px solid #e2e8f0;">

                @if ($category_id)
                    <div
                        style="background: #f0fdf4; border: 1px dashed #22c55e; border-radius: 12px; padding: 15px; margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div
                                    style="font-size: 11px; color: #166534; font-weight: 700; text-transform: uppercase;">
                                    Stok Buku (Nasabah)</div>
                                <div style="font-size: 24px; font-weight: 800; color: #15803d;">
                                    {{ number_format($stokInfo['total_weight'], 2) }} <span
                                        style="font-size: 14px;">Kg</span>
                                </div>
                            </div>

                            @if ($total_weight > 0)
                                <div style="text-align: right;">
                                    <div
                                        style="font-size: 11px; color: #1e40af; font-weight: 700; text-transform: uppercase;">
                                        Selisih Profit Timbangan</div>
                                    <div style="font-size: 20px; font-weight: 800; color: #1d4ed8;">
                                        {{ number_format($total_weight - $stokInfo['total_weight'], 2) }} Kg
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div
                            style="margin-top: 10px; font-size: 12px; color: #166534; border-top: 1px solid #dcfce7; pt-2;">
                            <i class="fas fa-info-circle"></i> Sebanyak <b>{{ $stokInfo['total_setoran'] }}</b> setoran
                            nasabah akan diproses.
                        </div>
                    </div>
                @else
                    <div
                        style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 15px; margin-bottom: 25px; text-align: center;">
                        <div style="font-size: 13px; color: #64748b;">Pilih kategori untuk melihat ketersediaan stok
                        </div>
                    </div>
                @endif

                <h5 style="font-weight: 700; margin-bottom: 20px; color: #1e293b;">
                    {{ $isEdit ? 'Update Penjualan' : 'Input Penjualan' }}
                </h5>

                @if (session()->has('message'))
                    <div
                        style="padding: 10px; background: #dcfce7; color: #15803d; border-radius: 8px; font-size: 13px; margin-bottom: 15px;">
                        {{ session('message') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div
                        style="padding: 10px; background: #fee2e2; color: #b91c1c; border-radius: 8px; font-size: 13px; margin-bottom: 15px;">
                        {{ session('error') }}
                    </div>
                @endif

                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" wire:key="form-sale-{{ $formKey }}">

                    <div style="margin-bottom: 15px; position: relative;">
                        <label
                            style="display: block; font-weight: 600; color: #64748b; margin-bottom: 8px; font-size: 14px;">
                            Kategori Sampah
                        </label>

                        @if ($category_id && ($selectedCat = $categories->find($category_id)))
                            {{-- TAMPILAN BADGE (Jika sudah dipilih) --}}
                            <div
                                style="display: flex; align-items: center; justify-content: space-between; background: #f8fafc; padding: 12px 15px; border-radius: 10px; border: 2px solid #e2e8f0;">
                                <div>
                                    <span
                                        style="font-size: 10px; color: #64748b; display: block; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px;">Kategori
                                        Terpilih</span>
                                    <span
                                        style="font-size: 15px; font-weight: 700; color: #1e293b;">{{ $selectedCat->name }}</span>
                                </div>
                                <button type="button" wire:click="$set('category_id', null)"
                                    style="background: #fee2e2; color: #ef4444; border: 1px solid #fecaca; padding: 6px 15px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; transition: 0.2s;">
                                    <i class="fas fa-times"></i> Ganti
                                </button>
                            </div>
                        @else
                            {{-- TAMPILAN SEARCH (Jika belum dipilih) --}}
                            <div style="position: relative;">
                                <div style="position: absolute; left: 12px; top: 12px; color: #94a3b8;">
                                    <i class="fas fa-search"></i>
                                </div>
                                <input type="text" wire:model.live="searchCategory"
                                    placeholder="Ketik nama kategori..." class="form-control shadow-none"
                                    style="width: 100%; border-radius: 10px; border: 1px solid #cbd5e1; padding: 12px 12px 12px 35px; font-size: 14px; background: #ffffff;">

                                {{-- Dropdown Hasil Pencarian --}}
                                @if (!empty($searchCategory))
                                    <div
                                        style="position: absolute; z-index: 999; width: 100%; background: white; border: 1px solid #cbd5e1; border-radius: 10px; margin-top: 5px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); max-height: 250px; overflow-y: auto;">
                                        @php
                                            $filtered = $categories->filter(
                                                fn($c) => str_contains(
                                                    strtolower($c->name),
                                                    strtolower($searchCategory),
                                                ),
                                            );
                                        @endphp

                                        @forelse($filtered as $cat)
                                            <div wire:click="$set('category_id', {{ $cat->id }})"
                                                style="padding: 12px 15px; cursor: pointer; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #334155; display: flex; align-items: center; gap: 10px;"
                                                onmouseover="this.style.backgroundColor='#f1f5f9'"
                                                onmouseout="this.style.backgroundColor='white'">
                                                <i class="fas fa-tag" style="color: #cbd5e1; font-size: 12px;"></i>
                                                {{ $cat->name }}
                                            </div>
                                        @empty
                                            <div
                                                style="padding: 15px; color: #94a3b8; font-size: 14px; text-align: center;">
                                                Kategori "{{ $searchCategory }}" tidak ditemukan
                                            </div>
                                        @endforelse
                                    </div>
                                @endif
                            </div>
                        @endif

                        @error('category_id')
                            <span
                                style="color: #ef4444; font-size: 11px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label
                            style="display: block; font-weight: 600; color: #64748b; margin-bottom: 8px; font-size: 14px;">
                            Nama Pengepul / Pembeli
                        </label>
                        <input type="text" wire:model.live="buyer_name"
                            wire:key="field-buyer-{{ $isEdit ? 'edit' : 'add' }}" class="form-control shadow-none"
                            style="width: 100%;border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px; font-size: 14px;"
                            placeholder="Contoh: UD Jaya Abadi...">
                        @error('buyer_name')
                            <span style="color: #ef4444; font-size: 11px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label
                            style="display: block; font-weight: 600; color: #64748b; margin-bottom: 8px; font-size: 14px;">
                            Tanggal Penjualan
                        </label>
                        <input type="date" wire:model.live="sale_date" class="form-control shadow-none"
                            style="width: 100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px; font-size: 14px;">
                        @error('sale_date')
                            <span style="color: #ef4444; font-size: 11px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label
                            style="display: block; font-weight: 600; color: #64748b; margin-bottom: 8px; font-size: 14px;">
                            Total Berat Riil (Kg)
                        </label>
                        <input type="number" step="0.01" wire:model.live="total_weight"
                            wire:key="field-weight-{{ $isEdit ? 'edit' : 'add' }}" class="form-control shadow-none"
                            style="width: 100%;border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px; font-size: 14px;"
                            placeholder="Berat timbangan pengepul...">
                        @error('total_weight')
                            <span style="color: #ef4444; font-size: 11px;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Harga Per Kg --}}
                    <div style="margin-bottom: 15px;">
                        <label
                            style="display: block; font-weight: 600; color: #64748b; margin-bottom: 8px; font-size: 14px;">
                            Harga Jual per Kg (Rp)
                        </label>
                        <input type="number" wire:model.live="price_per_kg"
                            wire:key="field-pricekg-{{ $isEdit ? 'edit' : 'add' }}" class="form-control shadow-none"
                            style="width: 100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px; font-size: 14px;"
                            placeholder="Harga satuan hari ini...">
                        @error('price_per_kg')
                            <span style="color: #ef4444; font-size: 11px;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- <div style="margin-bottom: 15px;">
                        <label
                            style="display: block; font-weight: 600; color: #64748b; margin-bottom: 8px; font-size: 14px;">
                            Total Harga Terima (Rp)
                        </label>
                        <input type="number" wire:model.live="total_price"
                            wire:key="field-price-{{ $isEdit ? 'edit' : 'add' }}" class="form-control shadow-none"
                            style="width: 100%;border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px; font-size: 14px; background-color: #f8fafc;"
                            placeholder="Otomatis (Berat x Harga)" readonly>
                        @error('total_price')
                            <span style="color: #ef4444; font-size: 11px;">{{ $message }}</span>
                        @enderror
                    </div> --}}

                    <div style="margin-bottom: 15px;">
                        <label
                            style="display: block; font-weight: 600; color: #64748b; margin-bottom: 8px; font-size: 14px;">Total
                            Terima (Rp)</label>
                        <div
                            style="width: 100%; border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px; font-size: 16px; font-weight: 700; background: #f8fafc; color: #1e293b;">
                            Rp {{ number_format((float) ($total_price ?? 0), 0, ',', '.') }}
                        </div>
                        {{-- Input hidden agar total_price tetap terkirim saat submit --}}
                        <input type="hidden" wire:model="total_price">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label
                            style="display: block; font-weight: 600; color: #64748b; margin-bottom: 8px; font-size: 14px;">
                            Catatan Tambahan
                        </label>
                        <textarea wire:model.live="note" wire:key="field-note-{{ $isEdit ? 'edit' : 'add' }}" class="form-control shadow-none"
                            style="width: 100%;border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px; font-size: 14px;" rows="2"
                            placeholder="Opsional..."></textarea>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-primary"
                            style="flex: 1; background: #4338ca; border: none; border-radius: 8px; padding: 10px; font-weight: 600; font-size: 14px; transition: 0.2s;">
                            {{ $isEdit ? 'Perbarui Data' : 'Simpan Penjualan' }}
                        </button>
                        @if ($isEdit)
                            <button type="button" wire:click="resetInput" class="btn btn-light"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px; font-weight: 600; font-size: 14px;">
                                Batal
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div
                style="background: white; border-radius: 15px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">

                <div style="padding: 15px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <div style="position: relative;">
                        <span style="position: absolute; left: 12px; top: 11px; color: #94a3b8;">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            placeholder="Cari nama pengepul..."
                            style="width: 100%; padding: 8px 12px 8px 35px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 14px;">
                    </div>
                </div>

                <div class="table-responsive">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                            <tr>
                                <th style="padding: 15px; font-size: 12px; color: #64748b; text-transform: uppercase;">
                                    Penjualan</th>
                                <th style="padding: 15px; font-size: 12px; color: #64748b; text-transform: uppercase;">
                                    Detail</th>
                                <th
                                    style="padding: 15px; font-size: 12px; color: #64748b; text-transform: uppercase; text-align: center;">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr style="border-bottom: 1px solid #f1f5f9;"
                                    wire:key="sale-row-{{ $sale->id }}">
                                    <td style="padding: 15px;">
                                        <div style="font-weight: 600; color: #1e293b;">{{ $sale->buyer_name }}</div>
                                        <div style="font-size: 12px; color: #94a3b8;">
                                            {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}
                                            <span
                                                style="margin-left: 5px; color: #6366f1;">({{ $sale->category->name ?? '-' }})</span>
                                        </div>
                                    </td>
                                    <td style="padding: 15px;">
                                        <div style="font-size: 13px; font-weight: 600; color: #059669;">
                                            Rp {{ number_format($sale->total_price, 0, ',', '.') }}
                                        </div>
                                        <div style="font-size: 12px; color: #64748b;">
                                            {{ $sale->total_weight }} Kg (Rp
                                            {{ number_format($sale->price_per_kg, 0, ',', '.') }}/Kg)
                                        </div>
                                    </td>
                                    <td style="padding: 15px; text-align: center;">
                                        <button wire:click="edit({{ $sale->id }})"
                                            class="btn btn-sm btn-light text-primary border"><i
                                                class="fas fa-edit"></i></button>
                                        <button
                                            onclick="confirm('Hapus riwayat penjualan ini? Saldo nasabah tidak akan otomatis kembali.') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $sale->id }})"
                                            class="btn btn-sm btn-light text-danger border"><i
                                                class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="padding: 20px; text-align: center; color: #94a3b8;">
                                        Belum ada data penjualan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div
                    style="padding: 15px; background: white; border-top: 1px solid #f1f5f9; display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 15px;">
                    <style>
                        .pagination-info {
                            display: block;
                            flex-grow: 1;
                            color: #64748b;
                            font-size: 13px;
                        }

                        @media (max-width: 640px) {
                            .pagination-info {
                                display: none;
                            }
                        }
                    </style>

                    <div class="pagination-info">
                        Menampilkan <b>{{ $sales->firstItem() ?? 0 }}</b> - <b>{{ $sales->lastItem() ?? 0 }}</b> dari
                        <b>{{ $sales->total() }}</b>
                    </div>

                    <div style="display: flex; gap: 10px; align-items: center; justify-content: center;">
                        <button type="button" wire:click="previousPage"
                            {{ $sales->onFirstPage() ? 'disabled' : '' }}
                            style="padding: 8px 16px; border-radius: 10px; border: 1px solid {{ $sales->onFirstPage() ? '#e2e8f0' : '#cbd5e1' }}; background: {{ $sales->onFirstPage() ? '#f8fafc' : 'white' }}; color: {{ $sales->onFirstPage() ? '#94a3b8' : '#475569' }}; cursor: {{ $sales->onFirstPage() ? 'not-allowed' : 'pointer' }};">
                            <i class="fas fa-chevron-left"></i>
                        </button>

                        <div
                            style="min-width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 800; color: #4338ca; background: #f0f2ff; border-radius: 10px; border: 1px solid #e0e7ff;">
                            {{ $sales->currentPage() }}
                        </div>

                        <button type="button" wire:click="nextPage" {{ !$sales->hasMorePages() ? 'disabled' : '' }}
                            style="padding: 8px 16px; border-radius: 10px; border: 1px solid {{ !$sales->hasMorePages() ? '#e2e8f0' : '#cbd5e1' }}; background: {{ !$sales->hasMorePages() ? '#f8fafc' : 'white' }}; color: {{ !$sales->hasMorePages() ? '#94a3b8' : '#475569' }}; cursor: {{ !$sales->hasMorePages() ? 'not-allowed' : 'pointer' }};">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @script
        <script>
            $wire.on('scroll-to-top', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        </script>
    @endscript
</div>
