<?php

namespace App\Policies;

use App\Models\MarketRate;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MarketRatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view market rates');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MarketRate $marketRate): bool
    {
        return $user->hasPermissionTo('view market rates');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create market rates');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MarketRate $marketRate): bool
    {
        return $user->hasPermissionTo('update market rates');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MarketRate $marketRate): bool
    {
        return $user->hasPermissionTo('delete market rates');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MarketRate $marketRate): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MarketRate $marketRate): bool
    {
        return false;
    }
}
