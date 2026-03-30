<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 

class payroll extends Model
{
 
 protected $fillable = [
        'payable_id',
        'payable_type',
        'refinery_id', 
        'period_start', 
        'status',
        'period_end',
        'payment_amount', 
        'payment_method', 
        'deductions',     
        'netpay',         
        'notes',
        'pay_date'
    ];

protected $casts = [
        'period_start' => 'date',
        'period_end'   => 'date',
        'paid_at'      => 'datetime',
        'base_salary'  => 'decimal:2',
        'commissions'  => 'decimal:2',
        'bonuses'      => 'decimal:2',
        'deductions'   => 'decimal:2',
        'net_pay'      => 'decimal:2',
    ];

   public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function refinery(): BelongsTo
    {
        return $this->belongsTo(Refinery::class);
    }

    
    protected static function booted()
    {
        static::saving(function ($payroll) {
            $payroll->net_pay = ($payroll->base_salary + $payroll->commissions + $payroll->bonuses) - $payroll->deductions;
        });
    }   
}
