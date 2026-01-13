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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Plastik PET, Kardus
            $table->enum('type', ['pilah', 'gabrukan']); // Kolom yang hilang
            $table->enum('profit_method', ['percentage', 'flat']);
            $table->decimal('profit_value', 12, 2);
            $table->decimal('current_selling_price', 12, 2)->default(0);
            $table->timestamps();
        });
        // Transaksi Utama
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id'); // Nasabah
            $table->enum('status', ['pending', 'sold']); // pending=di gudang, sold=terjual ke pengepul
            $table->timestamps();
        });

        // Detail Transaksi (Bisa banyak jenis sampah dalam 1 kedatangan)
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id');
            $table->foreignId('category_id');
            $table->decimal('weight', 8, 2);
            $table->decimal('price_to_nasabah', 12, 2); // Harga fix gabrukan atau dinamis pilah
            $table->timestamps();
        });

        // Insentif Petugas
        Schema::create('officer_incentives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id');
            $table->foreignId('officer_id');
            $table->decimal('amount', 12, 2)->default(2000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_tables');
    }
};
