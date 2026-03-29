<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone_number', 'address',
        'role', 'refinery_id', 'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role helpers
    public function isSystemAdmin(): bool   { return $this->role === 'system_admin'; }
    public function isRefineryAdmin(): bool { return $this->role === 'refinery_admin'; }
    public function isSalesManager(): bool  { return $this->role === 'sales_manager'; }

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, ['system_admin', 'refinery_admin', 'sales_manager']);
    }

    public function refinery(): BelongsTo
    {
        return $this->belongsTo(Refinery::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'sales_manager_id');
    }
}
