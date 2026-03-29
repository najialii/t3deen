<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Machine extends Model
{
    protected $fillable = ['name',
     'unit',
      'price_per_unit',
       'refinery_id',
        'is_active'
        ];

    protected $casts = ['is_active' => 'boolean', 'price_per_unit' => 'decimal:4'];

public static array $units = [
    'KG' => 'كيلو جرام (KG)',
    'SAG' => 'صاع (SAG)',
    'Gram' => 'جرام (Gram)',
    'Ton' => 'طن (Ton)',
    'Ounce' => 'أونصة (Ounce)',
];
    public function refinery(): BelongsTo
    {
        return $this->belongsTo(Refinery::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
