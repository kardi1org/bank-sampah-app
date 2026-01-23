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
        Schema::table('sales', function (Blueprint $table) {
            // Penjualan kolektif dilakukan per kategori
            $table->foreignId('category_id')->default(1)->constrained('categories')->after('id');

            // Harga jual ke pengepul saat itu (harga fluktuatif)
            $table->decimal('price_per_kg', 12, 2)->after('total_weight');
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['category_id', 'price_per_kg']);
        });
    }
};
