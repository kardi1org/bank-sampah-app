<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            // Mengetahui apakah item ini sudah terjual atau masih di gudang
            $table->enum('status', ['pending', 'sold'])->default('pending')->after('price_to_nasabah');

            // Link ke nota penjualan pengepul
            $table->foreignId('sale_id')->nullable()->constrained('sales')->onDelete('set null')->after('status');

            // Tanggal kapan barang ini terjual (untuk laporan nasabah)
            $table->date('sold_at')->nullable()->after('sale_id');

            // Berat riil setelah ditimbang pengepul (untuk menghitung penyusutan)
            $table->decimal('weight_at_sale', 8, 2)->nullable()->after('weight');
        });
    }

    public function down()
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropColumn(['status', 'sale_id', 'sold_at', 'weight_at_sale']);
        });
    }
};
