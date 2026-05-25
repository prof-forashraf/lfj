<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Determines if the user can access Filament admin panel based on:
     * 1. Explicit 'Super-Admin' or 'admin' roles
     * 2. 'access admin panel' permission (if it exists)
     *
     * This logic mirrors User::canAccessPanel() for consistency.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Check if user has explicit admin roles
        $isFilamentAdmin = $this->hasAnyRole(['Super-Admin', 'admin']);
        
        // If no explicit role, check for 'access admin panel' permission
        // Only check permission if it exists in the database (safety check)
        if (!$isFilamentAdmin && \Spatie\Permission\Models\Permission::where('name', 'access admin panel')->exists()) {
            $isFilamentAdmin = $this->hasPermissionTo('access admin panel');
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'roles' => $this->roles->pluck('name')->toArray(),
            'can_access_filament' => $isFilamentAdmin,
            'redirect_to' => $isFilamentAdmin ? '/admin' : '/dashboard',
            // Add other public fields if needed, e.g., avatar_url
            // DO NOT include email or other sensitive info unless intended for specific authenticated contexts
        ];
    }
}