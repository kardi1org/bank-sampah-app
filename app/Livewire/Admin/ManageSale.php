<?php

namespace App\Livewire\Admin;

use App\Models\Sale;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ManageSale extends Component
{
    use WithPagination;

    public $saleId, $buyer_name, $sale_date, $total_weight, $total_price, $note;
    public $isEdit = false;
    public $search = '';
    public $formKey; // Tambahkan properti ini
    public $category_id, $price_per_kg;
    public $searchCategory = ''; // Tambahkan ini di bagian atas class

    // Tambahkan fungsi ini agar saat kategori dipilih, input pencarian dibersihkan
    public function updatedCategoryId($value)
    {
        if ($value) {
            $this->searchCategory = '';
        }
    }

    public function mount()
    {
        $this->formKey = rand(); // Beri nilai awal
        $this->sale_date = now()->format('Y-m-d');
    }

    // Tambahkan di dalam class ManageSale

    public function render()
    {
        $stokInfo = ['total_weight' => 0, 'total_setoran' => 0];

        if ($this->category_id) {
            $details = \App\Models\TransactionDetail::where('category_id', $this->category_id)
                ->where('status', 'pending')
                ->get();

            $stokInfo['total_weight'] = $details->sum('weight');
            $stokInfo['total_setoran'] = $details->count();
        }
        $allCategories = \App\Models\Category::where('name', 'NOT LIKE', '%gabrukan%')->get();
        return view('livewire.admin.manage-sale', [
            'sales' => Sale::with('category')->where('buyer_name', 'like', '%' . $this->search . '%')
                ->orderBy('sale_date', 'desc')
                ->paginate(10),
            // FILTER: Jangan munculkan kategori 'Gabrukan' di pilihan penjualan
            'categories' => $allCategories,
            'stokInfo' => $stokInfo
        ])->layout('layouts.app');
    }

    public function updated($propertyName)
    {
        // Setiap kali total_weight atau price_per_kg berubah, hitung total_price
        if ($propertyName == 'total_weight' || $propertyName == 'price_per_kg') {
            $weight = floatval($this->total_weight);
            $price = floatval($this->price_per_kg);
            $this->total_price = $weight * $price;
        }
    }

    public function store()
    {
        $this->validate([
            // Validasi: category_id harus unik pada tabel sales untuk sale_date yang dipilih
            'category_id' => [
                'required',
                'exists:categories,id',
                \Illuminate\Validation\Rule::unique('sales')->where(function ($query) {
                    return $query->where('sale_date', $this->sale_date);
                }),
            ],
            'buyer_name'   => 'required|min:3',
            'sale_date'    => 'required|date',
            'total_weight' => 'required|numeric|gt:0',
            'price_per_kg' => 'required|numeric|gt:0',
            'total_price'  => 'required|numeric',
        ], [
            // Pesan error kustom agar user paham
            'category_id.unique' => 'Kategori ini sudah diinput untuk tanggal tersebut. Gunakan fitur edit jika ingin mengubah.',
        ]);

        try {
            DB::transaction(function () {
                // Ambil detail setoran nasabah yang pending
                $details = \App\Models\TransactionDetail::where('category_id', $this->category_id)
                    ->where('status', 'pending')
                    ->get();

                // if ($details->isEmpty()) {
                //     throw new \Exception("Tidak ada stok sampah pending untuk kategori ini.");
                // }

                // 1. Simpan ke tabel Sales
                $sale = Sale::create([
                    'category_id'  => $this->category_id,
                    'buyer_name'   => $this->buyer_name,
                    'sale_date'    => $this->sale_date,
                    'total_weight' => $this->total_weight,
                    'price_per_kg' => $this->price_per_kg,
                    'total_price'  => $this->total_price,
                    'note'         => $this->note,
                ]);

                // 2. Update nasabah & detail (Logika Rasio)
                $totalWeightGudang = $details->sum('weight');

                foreach ($details as $detail) {
                    if ($detail->transaction && $detail->transaction->user) {

                        // Jika berat riil lebih besar dari stok (untung), nasabah tetap dapat haknya sesuai catatan.
                        // Jika berat riil lebih kecil (susut), nasabah kena potong rasio.
                        $effectiveRatio = ($this->total_weight > $totalWeightGudang) ? 1 : ($this->total_weight / $totalWeightGudang);

                        $adjustedWeight = $detail->weight * $effectiveRatio;
                        $nominalNasabah = $adjustedWeight * $this->price_per_kg;

                        $detail->update([
                            'status' => 'sold',
                            'sale_id' => $sale->id,
                            'sold_at' => $this->sale_date,
                            'weight_at_sale' => $adjustedWeight,
                            'price_to_nasabah' => $nominalNasabah,
                        ]);

                        $detail->transaction->user->increment('saldo', $nominalNasabah);
                    }
                }
            });

            session()->flash('message', 'Data penjualan berhasil disimpan.');
            $this->resetInput();
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $sale = Sale::findOrFail($id);
        $this->saleId       = $sale->id;
        $this->category_id  = $sale->category_id;
        $this->buyer_name   = $sale->buyer_name;
        $this->sale_date    = $sale->sale_date;
        $this->total_weight = $sale->total_weight;
        $this->price_per_kg = $sale->price_per_kg;
        $this->total_price  = $sale->total_price;
        $this->note         = $sale->note;

        $this->isEdit = true;
        $this->formKey = 'edit-' . $id;
        $this->dispatch('scroll-to-top');
    }

    public function update()
    {
        $this->validate([
            'category_id' => [
                'required',
                'exists:categories,id',
                // Abaikan ID yang sedang diedit agar tidak terkena validasi unik diri sendiri
                \Illuminate\Validation\Rule::unique('sales')->where(function ($query) {
                    return $query->where('sale_date', $this->sale_date);
                })->ignore($this->saleId),
            ],
            'buyer_name'   => 'required|min:3',
            'sale_date'    => 'required|date',
            'total_weight' => 'required|numeric|gt:0',
            'price_per_kg' => 'required|numeric|gt:0',
            'total_price'  => 'required|numeric',
        ]);

        try {
            DB::transaction(function () {
                $sale = Sale::findOrFail($this->saleId);

                // LANGKAH 1: Tarik balik saldo lama dan reset status detail sebelumnya
                $oldDetails = \App\Models\TransactionDetail::where('sale_id', $sale->id)->get();
                foreach ($oldDetails as $detail) {
                    if ($detail->transaction && $detail->transaction->user) {
                        $detail->transaction->user->decrement('saldo', $detail->price_to_nasabah);
                    }
                    $detail->update([
                        'status' => 'pending',
                        'sale_id' => null,
                    ]);
                }

                // LANGKAH 2: Update Data Penjualan Utama
                $sale->update([
                    'category_id'  => $this->category_id,
                    'buyer_name'   => $this->buyer_name,
                    'sale_date'    => $this->sale_date,
                    'total_weight' => $this->total_weight,
                    'price_per_kg' => $this->price_per_kg,
                    'total_price'  => $this->total_price,
                    'note'         => $this->note,
                ]);

                // LANGKAH 3: Hitung ulang berdasarkan data baru
                // Kita ambil detail yang baru saja di-reset tadi (atau yang baru masuk kategori ini)
                $newDetails = \App\Models\TransactionDetail::where('category_id', $this->category_id)
                    ->where('status', 'pending')
                    ->get();

                // if ($newDetails->isEmpty()) {
                //     throw new \Exception("Tidak ada stok sampah untuk kategori ini.");
                // }

                $totalWeightGudang = $newDetails->sum('weight');
                $effectiveRatio = ($this->total_weight > $totalWeightGudang) ? 1 : ($this->total_weight / $totalWeightGudang);

                foreach ($newDetails as $detail) {
                    $adjustedWeight = $detail->weight * $effectiveRatio;
                    $nominalNasabah = $adjustedWeight * $this->price_per_kg;

                    $detail->update([
                        'status' => 'sold',
                        'sale_id' => $sale->id,
                        'sold_at' => $this->sale_date,
                        'weight_at_sale' => $adjustedWeight,
                        'price_to_nasabah' => $nominalNasabah,
                    ]);

                    if ($detail->transaction && $detail->transaction->user) {
                        $detail->transaction->user->increment('saldo', $nominalNasabah);
                    }
                }
            });

            $this->isEdit = false;
            $this->resetInput();
            session()->flash('message', 'Data penjualan dan saldo nasabah berhasil diperbarui.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    public function resetInput()
    {
        $this->reset([
            'buyer_name', 'sale_date', 'total_weight', 'total_price',
            'note', 'saleId', 'isEdit', 'category_id', 'price_per_kg'
        ]);
        $this->resetValidation();
        $this->formKey = rand();
    }

    public function delete($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $sale = Sale::findOrFail($id);

                // Ambil semua detail yang terjual dalam nota ini
                $details = \App\Models\TransactionDetail::where('sale_id', $sale->id)->get();

                foreach ($details as $detail) {
                    if ($detail->transaction && $detail->transaction->user) {
                        // 1. Tarik balik saldo nasabah (Kurangi saldo)
                        $detail->transaction->user->decrement('saldo', $detail->price_to_nasabah);
                    }

                    // 2. Kembalikan detail ke status pending dan kosongkan data penjualan
                    $detail->update([
                        'status' => 'pending',
                        'sale_id' => null,
                        'sold_at' => null,
                        'weight_at_sale' => null,
                        'price_to_nasabah' => 0,
                    ]);
                }

                // 3. Hapus data penjualan
                $sale->delete();
            });

            session()->flash('message', 'Data penjualan dihapus, stok dikembalikan ke gudang, dan saldo nasabah telah disesuaikan.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
