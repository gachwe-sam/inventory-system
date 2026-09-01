<?php

namespace Database\Seeders;

use App\Support\StockPermissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Spatie requires every permission/role to belong to a "guard" —
        // the authentication system it applies to. Your app only has
        // one, 'web' (Laravel's default session-based guard), so every
        // row below is created explicitly against it.
        $guard = 'web';

        // StockPermissions::ALL is already your single source of truth
        // for every permission NAME this app knows about. Looping over
        // it means adding a new permission later is a one-line change
        // in StockPermissions.php — this seeder picks it up automatically.
        foreach (StockPermissions::ALL as $permissionName) {
            // firstOrCreate: find a row matching these attributes, create
            // it only if missing. This makes the seeder SAFE TO RE-RUN —
            // running it twice won't duplicate rows or throw errors.
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => $guard,
            ]);
        }

        // The three roles your app's UI already expects to exist.
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);
        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => $guard]);
        Role::firstOrCreate(['name' => 'staff', 'guard_name' => $guard]);

        // Give admin every stock permission directly — any user with the
        // 'admin' role gets full stock access without ticking checkboxes
        // by hand on the Users screen.
        // syncPermissions() REPLACES the role's permissions with exactly
        // this list — safe to re-run, always ends up correct either way.
        $admin->syncPermissions(StockPermissions::ALL);

        // Managers get only what they're allowed to hand down to staff —
        // matches StockPermissions::MANAGER_ASSIGNABLE exactly.
        $manager->syncPermissions(StockPermissions::MANAGER_ASSIGNABLE);

        // 'staff' intentionally gets nothing here — individual staff
        // permissions are granted one at a time by their branch manager,
        // via BranchManagerStaffController.
    }
}