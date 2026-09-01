<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Hardcoded test user removed — you'll create your own below.
        // Check RoleSeeder.php / PermissionSeeder.php content before
        // deleting them — if they're the source of "branch_manager",
        // fold whatever's unique into RolePermissionSeeder and remove
        // the other two calls, so roles aren't defined in three places.
        $this->call([
            RolePermissionSeeder::class,
        ]);
    }
}