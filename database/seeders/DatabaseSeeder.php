<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Tania',
            'email' => 'tanias@example.com',
        ]);

        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call([ RolePermissionSeeder::class, ]);
    }
}
