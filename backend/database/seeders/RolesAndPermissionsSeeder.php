<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::firstOrCreate(['name' => 'access admin panel', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit articles', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete articles', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'publish articles', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'unpublish articles', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'manage users', 'guard_name' => 'web']);

        // Create roles and assign existing permissions
        $writerRole = Role::firstOrCreate(['name' => 'writer', 'guard_name' => 'web']);
        $writerRole->givePermissionTo(['edit articles', 'delete articles']);

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::where('guard_name', 'web')->get());

        $superAdminRole = Role::firstOrCreate(['name' => 'Super-Admin', 'guard_name' => 'web']);
        $superAdminRole->givePermissionTo(Permission::where('guard_name', 'web')->get());

        // Assign roles to seeded users
        $adminUser = User::where('email', 'admin@latestfashionjewellery.com')->first();
        if ($adminUser) {
            $adminUser->assignRole($superAdminRole);
        }

        $editorUser = User::where('email', 'editor@latestfashionjewellery.com')->first();
        if ($editorUser) {
            $editorUser->assignRole($writerRole);
        }
    }
}