<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficerIncentive extends Model
{
    // Tambahkan baris ini untuk mengizinkan input data
    protected $fillable = [
        'transaction_id',
        'officer_id',
        'amount',
    ];

    // Relasi ke User (Petugas)
    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }
}
