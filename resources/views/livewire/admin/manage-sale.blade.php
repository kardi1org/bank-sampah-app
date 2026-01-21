<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-md-4 mb-4">
            <div style="background: white; padding: 25px; border-radius: 15px; border: 1px solid #e2e8f0;">
                <h5 style="font-weight: 700; margin-bottom: 20px; color: #1e293b;">
                    {{ $isEdit ? 'Update Penjualan' : 'Input Penjualan' }}
                </h5>

                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" wire:key="form-sale-{{ $formKey }}">

                    {{-- @if ($isEdit)
                        <input type="hidden" wire:model="saleId">
                    @endif --}}

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
                        <input type="date" wire:model.live="sale_date"
                            wire:key="field-date-{{ $isEdit ? 'edit' : 'add' }}" class="form-control shadow-none"
                            style="width: 100%;border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px; font-size: 14px;">
                        @error('sale_date')
                            <span style="color: #ef4444; font-size: 11px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label
                            style="display: block; font-weight: 600; color: #64748b; margin-bottom: 8px; font-size: 14px;">
                            Total Berat (Kg)
                        </label>
                        <input type="number" step="0.01" wire:model.live="total_weight"
                            wire:key="field-weight-{{ $isEdit ? 'edit' : 'add' }}" class="form-control shadow-none"
                            style="width: 100%;border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px; font-size: 14px;"
                            placeholder="0.00">
                        @error('total_weight')
                            <span style="color: #ef4444; font-size: 11px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label
                            style="display: block; font-weight: 600; color: #64748b; margin-bottom: 8px; font-size: 14px;">
                            Total Harga Jual (Rp)
                        </label>
                        <input type="number" wire:model.live="total_price"
                            wire:key="field-price-{{ $isEdit ? 'edit' : 'add' }}" class="form-control shadow-none"
                            style="width: 100%;border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px; font-size: 14px;"
                            placeholder="Masukkan nominal harga...">
                        @error('total_price')
                            <span style="color: #ef4444; font-size: 11px;">{{ $message }}</span>
                        @enderror
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
                                <tr style="border-bottom: 1px solid #f1f5f9;" wire:key="sale-row-{{ $sale->id }}">
                                    <td style="padding: 15px;">
                                        <div style="font-weight: 600; color: #1e293b;">{{ $sale->buyer_name }}</div>
                                        <div style="font-size: 12px; color: #94a3b8;">
                                            {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</div>
                                    </td>
                                    <td style="padding: 15px;">
                                        <div style="font-size: 13px; font-weight: 600; color: #059669;">
                                            Rp {{ number_format($sale->total_price, 0, ',', '.') }}
                                        </div>
                                        <div style="font-size: 12px; color: #64748b;">{{ $sale->total_weight }} Kg
                                        </div>
                                    </td>
                                    <td style="padding: 15px; text-align: center;">
                                        <button wire:click="edit({{ $sale->id }})"
                                            class="btn btn-sm btn-light text-primary border"><i
                                                class="fas fa-edit"></i></button>
                                        <button
                                            onclick="confirm('Hapus riwayat penjualan ini?') || event.stopImmediatePropagation()"
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
                        <button type="button" wire:click="previousPage" {{ $sales->onFirstPage() ? 'disabled' : '' }}
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
    {{-- Script untuk scroll otomatis saat klik edit --}}
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
