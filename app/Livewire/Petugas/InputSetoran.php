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

    public function mount()
    {
        // Ambil semua kategori sekali saja saat halaman dibuka
        $this->searchCategory = [];
    }

    public function selectCategory($index, $categoryId, $categoryName)
    {
        $this->listSampah[$index]['category_id'] = $categoryId;
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
            'is_gabrukan' => ($type === 'gabrukan')
        ];

        // Inisialisasi search string untuk baris baru
        $index = count($this->listSampah) - 1;
        $this->searchCategory[$index] = '';
    }
    public function simpan()
    {
        $this->validate([
            'nasabahId' => 'required',
            'selectedOfficers' => 'required|array|min:1|max:3',
            'listSampah' => 'required|array|min:1',

            // Validasi Kategori: Wajib diisi jika is_gabrukan bernilai false
            'listSampah.*.category_id' => 'required_if:listSampah.*.is_gabrukan,false',

            // Validasi Berat: Wajib angka dan lebih dari 0
            'listSampah.*.weight' => 'required|numeric|gt:0',
        ], [
            'nasabahId.required' => 'Pilih nasabah terlebih dahulu.',
            'listSampah.*.category_id.required_if' => 'Jenis sampah wajib dipilih.',
            'listSampah.*.weight.gt' => 'Berat harus lebih dari 0 kg.',
        ]);

        DB::transaction(function () {
            $transaction = Transaction::create([
                'user_id' => $this->nasabahId,
                'status' => 'pending'
            ]);

            // Ambil ID kategori gabrukan sekali saja
            $gabrukanCategory = Category::where('type', 'gabrukan')->first();

            foreach ($this->listSampah as $item) {
                // Logika Perbaikan:
                // Jika is_gabrukan true, gunakan ID dari $gabrukanCategory
                // Jika tidak, gunakan category_id dari pilihan user
                $finalCategoryId = $item['is_gabrukan']
                    ? ($gabrukanCategory->id ?? null)
                    : $item['category_id'];

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'category_id' => $finalCategoryId, // Sekarang tidak akan kosong lagi
                    'weight' => $item['weight'],
                    'price_to_nasabah' => $item['is_gabrukan'] ? 500 : 0
                ]);
            }

            // Simpan Insentif Petugas...
            foreach ($this->selectedOfficers as $officerId) {
                OfficerIncentive::create([
                    'transaction_id' => $transaction->id,
                    'officer_id' => $officerId,
                    'amount' => 2000
                ]);
            }
        });

        $this->reset(['nasabahId', 'namaNasabah', 'searchNasabah', 'listSampah', 'searchCategory']);
        session()->flash('message', 'Setoran berhasil dicatat!');
    }

    // Hapus 'public $nasabahs;' jika ada di atas.

    public function render()
    {
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
            'nasabahs' => User::where('role', 'nasabah')
                ->where('name', 'like', '%' . $this->searchNasabah . '%')->get(),
            'allOfficers' => User::where('role', 'petugas')->get(),
            'todayTransactions' => $todayTransactions,
            'categoryResults' => $categoryResults // Kirim hasil cari kategori ke blade
        ]);
    }

    public function hapusBaris($index)
    {
        unset($this->listSampah[$index]);
        $this->listSampah = array_values($this->listSampah); // Reset urutan index
    }
}
