<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Determine if the user can access the Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // You can customize this logic further.
        // For example, if you have multiple panels, you can check $panel->getId()
        // if ($panel->getId() === 'admin') {
        //     return $this->hasRole(['Super-Admin', 'admin']);
        // }
        // return false;

        // Simplified: if the user has 'Super-Admin' or 'admin' role, they can access any panel
        // or specifically a permission like 'access admin panel'.
        if ($this->hasRole(['Super-Admin', 'admin'])) {
            return true;
        }

        // Avoid throwing an exception if the permission hasn't been created yet
        // (common in freshly-run test databases). Only check the permission
        // assignment if the permission exists.
        if (\Spatie\Permission\Models\Permission::where('name', 'access admin panel')->exists()) {
            return $this->hasPermissionTo('access admin panel');
        }

        return false;
    }

    public function isFilamentAdmin(): bool
    {
        return $this->canAccessPanel(Panel::make('admin'));
    }
}