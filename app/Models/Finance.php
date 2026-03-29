<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class Finance extends Model
{
    protected $fillable = [
        'refinery_id',
        'user_id',
        'category',
        'type',
        'amount',
        'reference_id',
        'reference_type',
        'entry_date',
        'payment_method',
        'description',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function refinery(): BelongsTo
    {
        return $this->belongsTo(Refinery::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function booted()
    {
        static::creating(function ($finance) {
            if (Auth::check() && ! $finance->user_id) {
                $finance->user_id = Auth::id();
            }
        });
    }
}