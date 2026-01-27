<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination; // Wajib untuk pagination
use Livewire\Attributes\Locked;

class KategoriSampah extends Component
{
    use WithPagination; // Gunakan trait ini

    // Gunakan tema pagination Bootstrap agar tampilannya rapi
    protected $paginationTheme = 'bootstrap';

    #[Locked]
    public $categoryId;

    public $name;
    public $price_type = 'percentage';
    public $nasabah_percentage = 80;
    public $price_fix = 0;
    public $type = 'pilah';
    public $isEdit = false;
    public $unit = 'kg';

    // Properti untuk pencarian
    public $search = '';

    // Reset halaman ke 1 setiap kali user mengetik pencarian
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetInput()
    {
        $this->reset(['name', 'categoryId', 'isEdit', 'price_fix', 'type']);
        $this->price_type = 'percentage';
        $this->unit = 'kg';
        $this->nasabah_percentage = 80;
        $this->resetValidation();
    }


    public function store()
    {
        $this->validate([
            'name' => 'required|min:3',
            'unit' => 'required|in:kg,ltr,pcs',
            'price_type' => 'required',
            'type' => 'required',
            'price_fix' => $this->price_type == 'fix' ? 'required|numeric' : 'nullable',
            'nasabah_percentage' => $this->price_type == 'percentage' ? 'required|numeric|max:100' : 'nullable',
        ]);

        Category::create([
            'name' => $this->name,
            'unit' => $this->unit,
            'price_type' => $this->price_type,
            'price_fix' => $this->price_type == 'fix' ? $this->price_fix : 0,
            'nasabah_percentage' => $this->price_type == 'percentage' ? $this->nasabah_percentage : 0,
            'type' => $this->type,
        ]);

        $this->resetInput();
        session()->flash('message', 'Kategori Berhasil Disimpan.');
    }

    public function edit($id)
    {
        $this->resetValidation();

        // Gunakan find() secara eksplisit dari Model
        $category = \App\Models\Category::where('id', $id)->first();

        if (!$category) {
            session()->flash('error', 'Data tidak ditemukan.');
            return;
        }

        // Pastikan mengisi ke property yang tepat
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->unit = $category->unit;
        $this->price_type = $category->price_type;
        $this->nasabah_percentage = $category->nasabah_percentage;
        $this->price_fix = $category->price_fix;
        $this->type = $category->type;

        $this->isEdit = true;
        $this->dispatch('scroll-to-top');
    }

    public function update()
    {
        // Debugging pertama: Cek apakah fungsi ini terpanggil
        // dd('Fungsi Update Berhasil Dipanggil dengan ID: ' . $this->categoryId);
        // dd($this->categoryId); // Hilangkan komentar ini untuk tes, jika muncul ID berarti fungsi masuk

        $this->validate([
            'name' => 'required|min:3',
            'unit' => 'required|in:kg,ltr,pcs',
            'price_type' => 'required',
        ]);

        if ($this->categoryId) {
            $category = Category::findOrFail($this->categoryId);

            // Gunakan update dengan array untuk memastikan Laravel Eloquent bekerja
            $category->update([
                'name'               => $this->name,
                'unit'               => $this->unit,
                'price_type'         => $this->price_type,
                'type'               => $this->type ?? 'pilah',
                'price_fix'          => ($this->price_type == 'fix') ? $this->price_fix : 0,
                'nasabah_percentage' => ($this->price_type == 'percentage') ? $this->nasabah_percentage : 0,
            ]);

            $this->resetInput();
            session()->flash('message', 'Data Berhasil Diperbarui!');
        }
    }

    public function delete($id)
    {
        Category::destroy($id);
        session()->flash('message', 'Kategori Berhasil Dihapus.');
    }

    public function render()
    {
        // Logika Query dengan Pencarian
        $categories = Category::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10); // Menampilkan 10 data per halaman

        return view('livewire.admin.kategori-sampah', [
            'categories' => $categories
        ])->layout('layouts.app');
    }
}
