<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-md-4 mb-4">
            <div style="background: white; padding: 25px; border-radius: 15px; border: 1px solid #e2e8f0;">
                <h5 style="font-weight: 700; margin-bottom: 20px; color: #1e293b;">
                    {{ $isEdit ? 'Update Pengguna' : 'Tambah Pengguna' }}
                </h5>

                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" wire:key="form-sale-{{ $formKey }}"
                    wire:key="form-user-{{ $userId ?? 'new' }}">
                    {{-- @if ($isEdit)
                        <input type="hidden" wire:model="userId">
                    @endif --}}
                    <div style="margin-bottom: 15px;">
                        <label
                            style="display: block; font-weight: 600; color: #64748b; margin-bottom: 8px; font-size: 14px;">Nama
                            Lengkap</label>
                        <input type="text" wire:model.live="name"
                            wire:key="field-name-{{ $isEdit ? 'edit' : 'add' }}" class="form-control shadow-none"
                            style="width: 100%;border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px; font-size: 14px;"
                            placeholder="Masukkan nama lengkap...">
                        @error('name')
                            <span style="color: #ef4444; font-size: 11px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label
                            style="display: block; font-weight: 600; color: #64748b; margin-bottom: 8px; font-size: 14px;">Alamat
                            Email</label>
                        <input type="email" wire:model.live="email"
                            wire:key="field-email-{{ $isEdit ? 'edit' : 'add' }}" class="form-control shadow-none"
                            style="width: 100%;border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px; font-size: 14px;"
                            placeholder="email@contoh.com">
                        @error('email')
                            <span style="color: #ef4444; font-size: 11px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label
                            style="display: block; font-weight: 600; color: #64748b; margin-bottom: 8px; font-size: 14px;">Role
                            / Peran</label>
                        <select wire:model.live="role" wire:key="field-role-{{ $isEdit ? 'edit' : 'add' }}"
                            class="form-select shadow-none"
                            style="width: 100%;border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px; font-size: 14px; cursor: pointer;">
                            <option value="nasabah">Nasabah</option>
                            <option value="petugas">Petugas</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label
                            style="display: block; font-weight: 600; color: #64748b; margin-bottom: 8px; font-size: 14px;">
                            Password {!! $isEdit ? '<span style="font-weight: 400; color: #94a3b8;">(Kosongkan jika tidak diubah)</span>' : '' !!}
                        </label>
                        <input type="password" wire:model.live="password"
                            wire:key="field-pass-{{ $isEdit ? 'edit' : 'add' }}" class="form-control shadow-none"
                            style="width: 100%;border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px; font-size: 14px;"
                            placeholder="••••••••">
                        @error('password')
                            <span style="color: #ef4444; font-size: 11px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-primary"
                            style="flex: 1; background: #4338ca; border: none; border-radius: 8px; padding: 10px; font-weight: 600; font-size: 14px; transition: 0.2s;">
                            {{ $isEdit ? 'Perbarui Data' : 'Simpan Data' }}
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
                            placeholder="Cari nama atau email..."
                            style="width: 100%; padding: 8px 12px 8px 35px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 14px;">
                    </div>
                </div>

                <div class="d-none d-md-block">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                            <tr>
                                <th style="padding: 15px; font-size: 12px; color: #64748b; text-transform: uppercase;">
                                    Pengguna</th>
                                <th style="padding: 15px; font-size: 12px; color: #64748b; text-transform: uppercase;">
                                    Role</th>
                                <th
                                    style="padding: 15px; font-size: 12px; color: #64748b; text-transform: uppercase; text-align: center;">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr style="border-bottom: 1px solid #f1f5f9;"
                                    wire:key="user-desktop-{{ $user->id }}">
                                    <td style="padding: 15px;">
                                        <div style="font-weight: 600; color: #1e293b;">{{ $user->name }}</div>
                                        <div style="font-size: 12px; color: #94a3b8;">{{ $user->email }}</div>
                                    </td>
                                    <td style="padding: 15px;">
                                        <span
                                            class="badge {{ $user->role == 'admin' ? 'bg-danger' : ($user->role == 'petugas' ? 'bg-warning' : 'bg-info') }}">
                                            {{ strtoupper($user->role) }}
                                        </span>
                                    </td>
                                    <td style="padding: 15px; text-align: center;">
                                        <button wire:click="edit({{ $user->id }})"
                                            class="btn btn-sm btn-light text-primary border"><i
                                                class="fas fa-edit"></i></button>
                                        <button onclick="confirm('Hapus?') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $user->id }})"
                                            class="btn btn-sm btn-light text-danger border"><i
                                                class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="padding: 20px; text-align: center; color: #94a3b8;">Data
                                        tidak ditemukan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

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
                        Menampilkan <b>{{ $users->firstItem() ?? 0 }}</b> -
                        <b>{{ $users->lastItem() ?? 0 }}</b> dari <b>{{ $users->total() }}</b>
                    </div>

                    {{-- Bagian Tengah/Kanan: Navigasi Utama --}}
                    <div style="display: flex; gap: 10px; align-items: center; justify-content: center;">
                        {{-- Tombol Previous --}}
                        <button type="button" wire:click="previousPage" {{ $users->onFirstPage() ? 'disabled' : '' }}
                            style="padding: 8px 16px; border-radius: 10px; border: 1px solid {{ $users->onFirstPage() ? '#e2e8f0' : '#cbd5e1' }}; background: {{ $users->onFirstPage() ? '#f8fafc' : 'white' }}; color: {{ $users->onFirstPage() ? '#94a3b8' : '#475569' }}; cursor: {{ $users->onFirstPage() ? 'not-allowed' : 'pointer' }}; font-size: 14px; font-weight: 600;">
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
                            {{ $users->currentPage() }}
                        </div>

                        {{-- Tombol Next --}}
                        <button type="button" wire:click="nextPage" {{ !$users->hasMorePages() ? 'disabled' : '' }}
                            style="padding: 8px 16px; border-radius: 10px; border: 1px solid {{ !$users->hasMorePages() ? '#e2e8f0' : '#cbd5e1' }}; background: {{ !$users->hasMorePages() ? '#f8fafc' : 'white' }}; color: {{ !$users->hasMorePages() ? '#94a3b8' : '#475569' }}; cursor: {{ !$users->hasMorePages() ? 'not-allowed' : 'pointer' }}; font-size: 14px; font-weight: 600;">
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
