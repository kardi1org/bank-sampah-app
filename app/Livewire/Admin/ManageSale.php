<?php

namespace App\Livewire\Admin;

use App\Models\Sale;
use Livewire\Component;
use Livewire\WithPagination;

class ManageSale extends Component
{
    use WithPagination;

    public $saleId, $buyer_name, $sale_date, $total_weight, $total_price, $note;
    public $isEdit = false;
    public $search = '';
    public $formKey; // Tambahkan properti ini

    public function mount()
    {
        $this->formKey = rand(); // Beri nilai awal
    }

    public function render()
    {
        $sales = Sale::where('buyer_name', 'like', '%' . $this->search . '%')
            ->orderBy('sale_date', 'desc')
            ->paginate(10);

        return view('livewire.admin.manage-sale', [
            'sales' => $sales
        ])->layout('layouts.app');
    }

    public function store()
    {
        $this->validate([
            'buyer_name' => 'required|min:3',
            'sale_date' => 'required|date',
            'total_weight' => 'required|numeric',
            'total_price' => 'required|numeric',
        ]);

        Sale::create([
            'buyer_name' => $this->buyer_name,
            'sale_date' => $this->sale_date,
            'total_weight' => $this->total_weight,
            'total_price' => $this->total_price,
            'note' => $this->note,
        ]);

        session()->flash('message', 'Data penjualan berhasil dicatat.');
        $this->resetInput();
    }

    public function edit($id)
    {
        $sale = Sale::findOrFail($id);
        $this->saleId = $sale->id;
        $this->buyer_name = $sale->buyer_name;
        $this->sale_date = $sale->sale_date;
        $this->total_weight = $sale->total_weight;
        $this->total_price = $sale->total_price;
        $this->note = $sale->note;

        $this->isEdit = true;

        // PAKSA Livewire merender ulang form dengan key baru
        $this->formKey = 'edit-' . $id;
        $this->dispatch('scroll-to-top');
    }
    public function update()
    {
        // Sekarang dd() ini pasti muncul jika tombol diklik
        //dd($this->saleId);

        $this->validate([
            'buyer_name'   => 'required|min:3',
            'sale_date'    => 'required|date',
            'total_weight' => 'required|numeric',
            'total_price'  => 'required|numeric',
        ]);

        try {
            if (!$this->saleId) {
                session()->flash('error', 'ID tidak ditemukan.');
                return;
            }

            $sale = Sale::findOrFail($this->saleId);

            $sale->update([
                'buyer_name'   => $this->buyer_name,
                'sale_date'    => $this->sale_date,
                'total_weight' => $this->total_weight,
                'total_price'  => $this->total_price,
                'note'         => $this->note,
            ]);

            session()->flash('message', 'Data penjualan berhasil diperbarui.');
            $this->resetInput(); // Form akan bersih kembali

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        Sale::destroy($id);
        session()->flash('message', 'Data berhasil dihapus.');
    }

    public function resetInput()
    {
        $this->reset(['buyer_name', 'sale_date', 'total_weight', 'total_price', 'note', 'saleId', 'isEdit']);
        $this->resetValidation();
        $this->formKey = rand(); // KUNCI: Ubah key hanya saat reset/selesai simpan
    }
}
