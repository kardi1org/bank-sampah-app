<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // use HasFactory;
    protected $fillable = [
        'name',
        'unit',
        'type',
        'price_type',
        'nasabah_percentage',
        'price_fix',
        'last_collector_price',
        'is_active',
        'profit_method',
        'profit_value',
        'current_selling_price',
        'profit_percentage'
    ];
}
