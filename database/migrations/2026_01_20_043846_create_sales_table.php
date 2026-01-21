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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('buyer_name'); // Nama Pengepul
            $table->date('sale_date');    // Tanggal Jual
            $table->decimal('total_weight', 8, 2); // Total berat (Kg)
            $table->decimal('total_price', 15, 2);  // Total uang diterima
            $table->text('note')->nullable();      // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
