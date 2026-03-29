<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'refinery_id',
        'customer_id',
        'machine_id',
        'worker_id',
        'sales_manager_id',
        'unit',
        'quantity',
        'price_per_unit',
        'notes',
        'status',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'price_per_unit' => 'decimal:4',
        'total_amount' => 'decimal:4',
    ];

    public function refinery(): BelongsTo
    {
        return $this->belongsTo(Refinery::class);
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function salesManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_manager_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
