<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'status', 'created_by', 'updated_by'];

    /**
     * Relasi ke Nasabah (User)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Detail Sampah
     */
    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Relasi ke Insentif Petugas
     */
    public function incentives(): HasMany
    {
        return $this->hasMany(OfficerIncentive::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
