{{-- <div class="container-fluid" style="padding: 20px; background: #f8fafc; min-height: 100vh;"> --}}
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h2
                style="color: #1e293b; font-weight: 700; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-tags" style="color: #4338ca;"></i>
                Master Kategori Sampah
            </h2>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert"
            style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
            <i class="fas fa-check-circle"></i> {{ session('message') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-4 mb-4">
            <div
                style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
                <h4 style="font-size: 18px; font-weight: 600; color: #334155; margin-bottom: 20px;">
                    {{ $isEdit ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
                </h4>

                <form wire:submit="{{ $isEdit ? 'update' : 'store' }}"
                    wire:key="form-kategori-{{ $isEdit ? 'edit' : 'create' }}">
                    <input type="hidden" wire:model="categoryId">
                    <div style="margin-bottom: 18px;">
                        <label
                            style="display: block; font-size: 13px; font-weight: 600; color: #64748b; margin-bottom: 8px;">Nama
                            Kategori</label>
                        <input type="text" wire:model="name" wire:key="input-name-{{ $categoryId ?? 'new' }}"
                            class="form-control"
                            style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;"
                            placeholder="Contoh: Plastik PET, Besi Tua">
                        @error('name')
                            <span style="color: #ef4444; font-size: 11px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 18px;">
                        <label
                            style="display: block; font-size: 13px; font-weight: 600; color: #64748b; margin-bottom: 8px;">Tipe
                            Perhitungan Harga</label>
                        <select wire:model.live="price_type" wire:key="select-price-{{ $categoryId ?? 'new' }}"
                            style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff;">
                            <option value="percentage">Fluktuatif (Persentase dari Pengepul)</option>
                            <option value="fix">Harga Fix / Gabrukan (Tetap)</option>
                        </select>
                    </div>

                    @if ($price_type == 'percentage')
                        <div
                            style="margin-bottom: 18px; background: #f0f4ff; padding: 15px; border-radius: 10px; border: 1px solid #dbeafe;">
                            <label
                                style="display: block; font-size: 13px; font-weight: 600; color: #1e40af; margin-bottom: 8px;">Bagi
                                Hasil Nasabah (%)</label>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <input type="number" wire:model="nasabah_percentage" class="form-control"
                                    style="flex: 1; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                                <span style="font-weight: bold; color: #1e40af;">%</span>
                            </div>
                            <p style="font-size: 11px; color: #64748b; margin-top: 8px; line-height: 1.4;">
                                *Nasabah akan menerima **{{ $nasabah_percentage ?: 0 }}%** dari harga jual ke pengepul.
                                Sisa **{{ 100 - ($nasabah_percentage ?: 0) }}%** menjadi profit bank sampah.
                            </p>
                        </div>
                    @else
                        <div
                            style="margin-bottom: 18px; background: #f0fdf4; padding: 15px; border-radius: 10px; border: 1px solid #dcfce7;">
                            <label
                                style="display: block; font-size: 13px; font-weight: 600; color: #166534; margin-bottom: 8px;">Harga
                                Tetap (Rp/Kg)</label>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="font-weight: bold; color: #166534;">Rp</span>
                                <input type="number" wire:model="price_fix" class="form-control"
                                    style="flex: 1; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;"
                                    placeholder="0">
                            </div>
                            <p style="font-size: 11px; color: #64748b; margin-top: 8px;">
                                *Harga ini tidak berubah meskipun harga pasar pengepul naik/turun.
                            </p>
                        </div>
                    @endif

                    <div style="display: flex; gap: 10px; margin-top: 25px;">
                        <button type="submit"
                            style="flex: 1; background: #4338ca; color: white; padding: 12px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s;">
                            <i class="fas fa-save"></i> {{ $isEdit ? 'Update' : 'Simpan' }}
                        </button>
                        @if ($isEdit)
                            <button type="button" wire:click="resetInput"
                                style="background: #64748b; color: white; padding: 12px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
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
                            placeholder="Cari kategori sampah..."
                            style="width: 100%; padding: 8px 12px 8px 35px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 14px;">
                    </div>
                </div>
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 15px; font-size: 13px; color: #475569; font-weight: 700;">NAMA KATEGORI
                            </th>
                            <th style="padding: 15px; font-size: 13px; color: #475569; font-weight: 700;">SKEMA HARGA
                            </th>
                            <th style="padding: 15px; font-size: 13px; color: #475569; font-weight: 700;">NILAI</th>
                            <th
                                style="padding: 15px; font-size: 13px; color: #475569; font-weight: 700; text-align: center;">
                                AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            {{-- Pastikan key ini selalu unik dan tidak berubah --}}
                            <tr wire:key="cat-row-{{ $category->id }}"
                                style="border-bottom: 1px solid #f1f5f9; transition: 0.2s;"
                                onmouseover="this.style.background='#fcfdff'"
                                onmouseout="this.style.background='transparent'">

                                <td style="padding: 15px; font-weight: 600; color: #1e293b;">{{ $category->name }}</td>

                                <td style="padding: 15px;">
                                    @if ($category->price_type == 'percentage')
                                        <span
                                            style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">FLUKTUATIF</span>
                                    @else
                                        <span
                                            style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">FIXED</span>
                                    @endif
                                </td>

                                <td style="padding: 15px;">
                                    @if ($category->price_type == 'percentage')
                                        <span
                                            style="font-weight: 700; color: #1e293b;">{{ $category->nasabah_percentage }}%</span>
                                    @else
                                        <span style="font-weight: 700; color: #1e293b;">Rp
                                            {{ number_format($category->price_fix, 0, ',', '.') }}</span>
                                    @endif
                                </td>

                                <td style="padding: 15px; text-align: center;">
                                    {{-- PERBAIKAN DI SINI: Tambahkan .prevent dan wire:key pada tombol --}}
                                    <button type="button" wire:click.prevent="edit({{ $category->id }})"
                                        wire:click="$call('edit', [{{ $category->id }}])"
                                        style="background: #f1f5f9; color: #4338ca; border: none; width: 35px; height: 35px; border-radius: 8px; cursor: pointer; transition: 0.3s;">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button type="button"
                                        onclick="confirm('Hapus kategori ini?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $category->id }})"
                                        wire:key="delete-btn-{{ $category->id }}"
                                        style="background: #fef2f2; color: #ef4444; border: none; width: 35px; height: 35px; border-radius: 8px; cursor: pointer; transition: 0.3s;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 40px; text-align: center; color: #94a3b8;">
                                    <i class="fas fa-folder-open"
                                        style="font-size: 40px; display: block; margin-bottom: 10px;"></i>
                                    Belum ada kategori sampah.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div
                    style="
                                padding: 15px;
                                background: white;
                                border-top: 1px solid #f1f5f9;
                                display: flex;
                                flex-wrap: wrap;
                                align-items: center;
                                justify-content: center; /* Membuat semua konten ke tengah di mobile */
                                gap: 15px;
                            ">
                    {{-- Bagian Kiri: Info (Hanya muncul di Desktop/Layar Lebar) --}}
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

                            /* Sembunyikan info teks di mobile agar fokus ke tombol */
                        }
                    </style>

                    <div class="pagination-info">
                        Menampilkan <b>{{ $categories->firstItem() ?? 0 }}</b> -
                        <b>{{ $categories->lastItem() ?? 0 }}</b> dari <b>{{ $categories->total() }}</b>
                    </div>

                    {{-- Bagian Tengah/Kanan: Navigasi Utama --}}
                    <div style="display: flex; gap: 10px; align-items: center; justify-content: center;">
                        {{-- Tombol Previous --}}
                        <button type="button" wire:click="previousPage"
                            {{ $categories->onFirstPage() ? 'disabled' : '' }}
                            style="padding: 8px 16px; border-radius: 10px; border: 1px solid {{ $categories->onFirstPage() ? '#e2e8f0' : '#cbd5e1' }}; background: {{ $categories->onFirstPage() ? '#f8fafc' : 'white' }}; color: {{ $categories->onFirstPage() ? '#94a3b8' : '#475569' }}; cursor: {{ $categories->onFirstPage() ? 'not-allowed' : 'pointer' }}; font-size: 14px; font-weight: 600;">
                            <i class="fas fa-chevron-left"></i>
                        </button>

                        {{-- Nomor Halaman --}}
                        <div
                            style="
                                    min-width: 40px;
                                    height: 40px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: 14px;
                                    font-weight: 800;
                                    color: #4338ca;
                                    background: #f0f2ff;
                                    border-radius: 10px;
                                    border: 1px solid #e0e7ff;
                                ">
                            {{ $categories->currentPage() }}
                        </div>

                        {{-- Tombol Next --}}
                        <button type="button" wire:click="nextPage"
                            {{ !$categories->hasMorePages() ? 'disabled' : '' }}
                            style="padding: 8px 16px; border-radius: 10px; border: 1px solid {{ !$categories->hasMorePages() ? '#e2e8f0' : '#cbd5e1' }}; background: {{ !$categories->hasMorePages() ? '#f8fafc' : 'white' }}; color: {{ !$categories->hasMorePages() ? '#94a3b8' : '#475569' }}; cursor: {{ !$categories->hasMorePages() ? 'not-allowed' : 'pointer' }}; font-size: 14px; font-weight: 600;">
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
