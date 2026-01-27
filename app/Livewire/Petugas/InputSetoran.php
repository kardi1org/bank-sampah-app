<?php

namespace App\Livewire\Petugas;

use Livewire\Component;
use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\OfficerIncentive;
use Illuminate\Support\Facades\DB;

class InputSetoran extends Component
{
    public $searchNasabah = '';
    public $nasabahId, $namaNasabah;
    public $listSampah = []; // Menampung input dinamis
    public $selectedOfficers = []; // Menampung ID petugas (max 3)
    public $searchCategory = [];
    public $showAllOfficers = false;
    public $editingTransactionId = null; // Tambahkan ini

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        // Ambil semua kategori sekali saja saat halaman dibuka
        $this->searchCategory = [];
        $this->showAllOfficers = true;
    }

    public function gantiPetugas()
    {
        $this->showAllOfficers = true;
        // Opsional: $this->selectedOfficers = []; // Hapus pilihan lama jika ingin benar-benar reset
    }

    public function selectCategory($index, $categoryId, $categoryName, $unit)
    {
        $this->listSampah[$index]['category_id'] = $categoryId;
        $this->listSampah[$index]['unit'] = $unit; // Simpan unit dari master ke sini
        $this->searchCategory[$index] = $categoryName;
    }

    public function resetCategory($index)
    {
        $this->listSampah[$index]['category_id'] = '';
        $this->searchCategory[$index] = '';
    }

    public function gantiNasabah()
    {
        $this->nasabahId = null;
        $this->namaNasabah = null;
        $this->searchNasabah = ''; // Pastikan search kosong saat mau ganti
    }

    public function selectNasabah($id, $name)
    {
        $this->nasabahId = $id;
        $this->namaNasabah = $name;
        $this->searchNasabah = ''; // Bersihkan teks agar saat di-reset sudah kosong
    }

    // public function tambahBaris($type)
    // {
    //     $category = Category::where('type', $type)->first();
    //     $this->listSampah[] = [
    //         'category_id' => $category->id ?? null,
    //         'category_name' => $type == 'gabrukan' ? 'Gabrukan' : 'Pilih Jenis',
    //         'weight' => 0,
    //         'is_gabrukan' => ($type == 'gabrukan')
    //     ];
    // }

    public function tambahBaris($type)
    {
        $this->listSampah[] = [
            'category_id' => '',
            'weight' => '',
            'unit' => ($type === 'gabrukan' ? 'Kg' : ''), // Tambahkan key unit
            'is_gabrukan' => ($type === 'gabrukan')
        ];

        $index = count($this->listSampah) - 1;
        $this->searchCategory[$index] = '';
    }

    public function simpan()
    {
        $this->validate([
            'nasabahId' => 'required',
            'selectedOfficers' => 'required|array|min:1|max:3', // Pastikan petugas dipilih
            'listSampah' => 'required|array|min:1',
            'listSampah.*.category_id' => 'required_if:listSampah.*.is_gabrukan,false',
            'listSampah.*.weight' => 'required|numeric|gt:0',
        ], [
            'nasabahId.required' => 'Pilih nasabah terlebih dahulu.',
            'selectedOfficers.required' => 'Pilih minimal 1 petugas yang bertugas.',
            'listSampah.*.category_id.required_if' => 'Jenis sampah wajib dipilih.',
            'listSampah.*.weight.gt' => 'Berat harus lebih dari 0 kg.',
        ]);

        DB::transaction(function () {
            // 1. Logika Update atau Create Transaksi
            if ($this->editingTransactionId) {
                $transaction = Transaction::find($this->editingTransactionId);
                $transaction->update([
                    'user_id' => $this->nasabahId,
                    'updated_by' => auth()->id(),
                ]);

                // 2. BERSIHKAN DATA LAMA (PENTING)
                // Hapus detail sampah dan daftar petugas lama sebelum menulis yang baru
                $transaction->details()->delete();
                $transaction->incentives()->delete();
            } else {
                $transaction = Transaction::create([
                    'user_id' => $this->nasabahId,
                    'created_by' => auth()->id(),
                    'status' => 'pending'
                ]);
            }

            $gabrukanCategory = Category::where('type', 'gabrukan')->first();

            // 3. SIMPAN DETAIL SAMPAH BARU
            foreach ($this->listSampah as $item) {
                $finalCategoryId = $item['is_gabrukan']
                    ? ($gabrukanCategory->id ?? null)
                    : $item['category_id'];

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'category_id' => $finalCategoryId,
                    'weight' => $item['weight'],
                    'price_to_nasabah' => $item['is_gabrukan'] ? 500 : 0
                ]);
            }

            // 4. SIMPAN PETUGAS BERTUGAS BARU (UPDATE TERHADAP PETUGAS)
            foreach ($this->selectedOfficers as $officerId) {
                OfficerIncentive::create([
                    'transaction_id' => $transaction->id,
                    'officer_id' => $officerId,
                    'amount' => 2000 // Nominal insentif per petugas
                ]);
            }
        });

        // Reset Form
        $this->reset(['nasabahId', 'namaNasabah', 'searchNasabah', 'listSampah', 'searchCategory', 'editingTransactionId']);

        // Opsional: Jika ingin petugas tetap terpilih setelah simpan, jangan reset selectedOfficers
        // Tapi kita sembunyikan daftar pilihannya agar rapi
        $this->showAllOfficers = false;

        session()->flash('message', $this->editingTransactionId ? 'Transaksi & Petugas berhasil diperbarui!' : 'Setoran berhasil dicatat!');
    }

    public function render()
    {
        // Ambil semua user yang berhak setor (nasabah, petugas, admin)
        $nasabahs = User::where(function ($query) {
            $query->where('role', 'nasabah')
                ->orWhere('role', 'petugas')
                ->orWhere('role', 'admin');
        })
            ->where('name', 'like', '%' . $this->searchNasabah . '%')
            ->take(5) // Batasi agar tidak terlalu panjang saat mengetik
            ->get();
        // Ambil transaksi hari ini
        $todayTransactions = Transaction::with(['user', 'details.category', 'incentives.officer'])
            ->whereDate('created_at', now())->latest()->get();

        // Logika pencarian kategori per baris
        $categoryResults = [];
        foreach ($this->listSampah as $index => $item) {
            if (!empty($this->searchCategory[$index]) && !$item['category_id']) {
                $categoryResults[$index] = Category::where('type', 'pilah')
                    ->where('name', 'like', '%' . $this->searchCategory[$index] . '%')
                    ->take(10)
                    ->get();
            } else {
                $categoryResults[$index] = collect();
            }
        }

        return view('livewire.petugas.input-setoran', [
            'nasabahs' => $nasabahs,
            'categories' => Category::where('type', 'pilah')->get(),
            'allOfficers' => User::where('role', 'petugas')->get(),
            'todayTransactions' => $todayTransactions,
            'categoryResults' => $categoryResults ?? []
        ]);
    }

    public function hapusBaris($index)
    {
        unset($this->listSampah[$index]);
        $this->listSampah = array_values($this->listSampah); // Reset urutan index
    }

    // Tambahkan fungsi ini di dalam class InputSetoran
    public function editTransaction($id)
    {
        $transaction = Transaction::with(['details.category', 'incentives', 'user'])->find($id);

        $this->editingTransactionId = $id;
        $this->nasabahId = $transaction->user_id;
        $this->namaNasabah = $transaction->user->name;

        $this->selectedOfficers = $transaction->incentives->pluck('officer_id')->map(fn ($id) => (string)$id)->toArray();
        $this->showAllOfficers = false;

        $this->listSampah = [];
        $this->searchCategory = []; // Reset search category

        foreach ($transaction->details as $index => $detail) {
            $is_gabrukan = ($detail->category && $detail->category->type === 'gabrukan') || is_null($detail->category_id);

            $this->listSampah[] = [
                'category_id' => $detail->category_id,
                'weight' => $detail->weight,
                'unit' => $detail->category->unit ?? 'Kg',
                'is_gabrukan' => $is_gabrukan
            ];

            // Isi searchCategory agar nama kategori muncul di input
            $this->searchCategory[$index] = $detail->category ? $detail->category->name : '';
        }

        $this->dispatch('scroll-to-top');
    }

    public function hapusTransaksi($id)
    {
        DB::transaction(function () use ($id) {
            $transaction = Transaction::find($id);

            if ($transaction) {
                // Hapus detail sampah terlebih dahulu
                $transaction->details()->delete();

                // Hapus insentif petugas terkait
                $transaction->incentives()->delete();

                // Baru hapus transaksi utamanya
                $transaction->delete();
            }
        });

        session()->flash('message', 'Transaksi dan detailnya berhasil dihapus');
    }

    public function batalEdit()
    {
        // Reset ID edit agar sistem tahu ini bukan lagi mode update
        $this->editingTransactionId = null;

        // Bersihkan data nasabah dan sampah
        $this->reset([
            'nasabahId',
            'namaNasabah',
            'searchNasabah',
            'listSampah',
            'searchCategory'
        ]);

        // Kembalikan tampilan petugas ke mode pilih (opsional)
        $this->showAllOfficers = true;

        session()->flash('message', 'Edit dibatalkan.');
    }
}
