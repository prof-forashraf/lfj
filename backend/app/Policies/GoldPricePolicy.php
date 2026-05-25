<?php

namespace App\Policies;

use App\Models\GoldPrice;
use App\Models\User;

class GoldPricePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, GoldPrice $goldPrice): bool
    {
        return true;
    }

    /**
     * Prevent all create/update/delete operations on gold_prices.
     * Gold prices are managed by the sync API only, never by admin UI.
     */
    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, GoldPrice $goldPrice): bool
    {
        return false;
    }

    public function delete(User $user, GoldPrice $goldPrice): bool
    {
        return false;
    }

    public function restore(User $user, GoldPrice $goldPrice): bool
    {
        return false;
    }

    public function forceDelete(User $user, GoldPrice $goldPrice): bool
    {
        return false;
    }
}
