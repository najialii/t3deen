<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Worker extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'phone', 'national_id', 'refinery_id', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function refinery(): BelongsTo
    {
        return $this->belongsTo(Refinery::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function payments(): HasMany
{
    return $this->hasMany(WorkerPay::class);
}
}
