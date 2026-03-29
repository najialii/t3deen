<?php

namespace App\Filament\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait ScopedToRefinery
{
    /**
     * Scope query to the authenticated user's refinery.
     * System admins see everything.
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        /** @var User $user */
        $user = Auth::user();

        if ($user->isSystemAdmin()) {
            return $query;
        }

        return $query->where('refinery_id', $user->refinery_id);
    }
}
