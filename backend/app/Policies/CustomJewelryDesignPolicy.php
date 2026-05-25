<?php

namespace App\Policies;

use App\Models\CustomJewelryDesign;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CustomJewelryDesignPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Users can view their own designs
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CustomJewelryDesign $customJewelryDesign): bool
    {
        return $user->id === $customJewelryDesign->user_id || $customJewelryDesign->is_public;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Authenticated users can create designs
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CustomJewelryDesign $customJewelryDesign): bool
    {
        return $user->id === $customJewelryDesign->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CustomJewelryDesign $customJewelryDesign): bool
    {
        return $user->id === $customJewelryDesign->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CustomJewelryDesign $customJewelryDesign): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CustomJewelryDesign $customJewelryDesign): bool
    {
        return false;
    }
}
