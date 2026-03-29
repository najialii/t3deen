<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Worker extends Model
{
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
}
