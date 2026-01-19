<?php

namespace App\Livewire\Petugas;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class DataNasabahTable extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Fungsi untuk mengarahkan ke halaman riwayat setoran nasabah tertentu
    public function lihatTransaksi($userId)
    {
        // Cek role user yang sedang login
        $role = auth()->user()->role;

        // Redirect sesuai prefix role masing-masing
        if ($role === 'admin') {
            return redirect()->route('admin.riwayat-hari-ini', ['user_id' => $userId]);
        } else {
            return redirect()->route('petugas.riwayat-hari-ini', ['user_id' => $userId]);
        }
    }

    public function render()
    {
        // Sekarang menampilkan semua user tanpa filter role nasabah saja
        $nasabahs = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.petugas.data-nasabah-table', [
            'nasabahs' => $nasabahs
        ]);
    }
}
