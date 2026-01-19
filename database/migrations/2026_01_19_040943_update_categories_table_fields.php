<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // 1. Hapus kolom yang duplikat/tidak perlu agar tidak membingungkan
            // (Hapus jika memang kolom ini sudah ada di tabel lama Anda)
            if (Schema::hasColumn('categories', 'profit_method')) {
                $table->dropColumn('profit_method');
            }
            if (Schema::hasColumn('categories', 'profit_value')) {
                $table->dropColumn('profit_value');
            }
            if (Schema::hasColumn('categories', 'profit_percentage')) {
                $table->dropColumn('profit_percentage');
            }
            if (Schema::hasColumn('categories', 'current_selling_price')) {
                $table->dropColumn('current_selling_price');
            }

            // 2. Tambah / Modifikasi kolom agar sesuai logika "Harga Pengepul"
            // Kita gunakan nasabah_percentage untuk menentukan hak nasabah
            if (!Schema::hasColumn('categories', 'nasabah_percentage')) {
                $table->decimal('nasabah_percentage', 5, 2)->default(80.00)->after('price_type');
            }

            // Kolom untuk harga fix (gabrukan)
            if (!Schema::hasColumn('categories', 'price_fix')) {
                $table->decimal('price_fix', 12, 2)->default(0)->after('nasabah_percentage');
            }

            // Kolom referensi harga pengepul terakhir (untuk estimasi)
            if (!Schema::hasColumn('categories', 'last_collector_price')) {
                $table->decimal('last_collector_price', 12, 2)->default(0)->after('price_fix');
            }

            // Memastikan kolom type memiliki opsi yang benar
            // Note: change() membutuhkan package 'doctrine/dbal'
            // Jika error saat migrate, lewati bagian change() ini
            $table->enum('type', ['pilah', 'gabrukan'])->default('pilah')->change();
            $table->enum('price_type', ['fix', 'percentage'])->default('percentage')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['nasabah_percentage', 'price_fix', 'last_collector_price']);
            // Tambahkan kembali kolom yang dihapus jika ingin rollback (opsional)
        });
    }
};
