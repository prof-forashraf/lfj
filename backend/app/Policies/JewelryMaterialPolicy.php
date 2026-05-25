<?php

namespace App\Policies;

use App\Models\JewelryMaterial;
use App\Models\User;

class JewelryMaterialPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['Super-Admin', 'admin']);
    }

    public function view(User $user, JewelryMaterial $jewelryMaterial): bool
    {
        return $user->hasRole(['Super-Admin', 'admin']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['Super-Admin', 'admin']);
    }

    public function update(User $user, JewelryMaterial $jewelryMaterial): bool
    {
        return $user->hasRole(['Super-Admin', 'admin']) && $jewelryMaterial->is_auto_synced === false;
    }

    public function delete(User $user, JewelryMaterial $jewelryMaterial): bool
    {
        return $user->hasRole(['Super-Admin', 'admin']) && $jewelryMaterial->is_auto_synced === false;
    }

    public function restore(User $user, JewelryMaterial $jewelryMaterial): bool
    {
        return false;
    }

    public function forceDelete(User $user, JewelryMaterial $jewelryMaterial): bool
    {
        return false;
    }
}
