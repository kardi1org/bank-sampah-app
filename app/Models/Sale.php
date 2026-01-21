<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'buyer_name',
        'sale_date',
        'total_weight',
        'total_price',
        'note'
    ];
}
