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
        'status',
        'sale_id',
        'sold_at',
        'weight_at_sale'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // app/Models/TransactionDetail.php

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
