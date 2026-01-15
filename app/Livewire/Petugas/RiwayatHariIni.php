<?php

namespace App\Livewire\Petugas;

use App\Models\Transaction;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination; // 1. Import trait pagination

class RiwayatHariIni extends Component
{
    use WithPagination; // 2. Gunakan trait di dalam class

    public $userId;

    public function mount($user_id = null)
    {
        $this->userId = $user_id;
    }

    public function render()
    {
        $query = Transaction::with(['user', 'details.category']);

        if ($this->userId) {
            // Filter nasabah tertentu (semua waktu)
            $query->where('user_id', $this->userId);
        } else {
            // Filter riwayat hari ini saja
            $query->whereDate('created_at', now());
        }

        return view('livewire.petugas.riwayat-hari-ini', [
            // 3. Ubah get() menjadi paginate(10)
            'todayTransactions' => $query->latest()->paginate(10),
            'targetUser' => $this->userId ? User::find($this->userId) : null
        ]);
    }
}
