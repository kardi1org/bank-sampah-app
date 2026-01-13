<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    // Tambahkan baris ini
    protected $fillable = [
        'transaction_id',
        'category_id',
        'weight',
        'price_to_nasabah',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
