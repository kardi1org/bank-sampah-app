<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kategori Gabrukan (Hanya 1)
        \App\Models\Category::create([
            'name' => 'Sampah Campur (Gabrukan)',
            'type' => 'gabrukan',
            'profit_method' => 'flat',
            'profit_value' => 500, // Harga beli ke nasabah
        ]);

        // Kategori Pilah (Banyak)
        \App\Models\Category::create([
            'name' => 'Plastik PET',
            'type' => 'pilah',
            'profit_method' => 'percentage',
            'profit_value' => 20, // Potongan 20%
        ]);
    }
}
