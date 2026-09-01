<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RouteAuthorizationTest extends TestCase
{
    // RefreshDatabase gives every single test method its own clean,
    // freshly-migrated database — tests never see leftover data from
    // each other, and never touch your real dev database (Laravel's
    // testing environment, configured in phpunit.xml, points at a
    // separate connection — worth confirming that file says
    // DB_CONNECTION=sqlite / DB_DATABASE=:memory: before running this,
    // so you're not accidentally wiping real data).
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp(); // always call this first — it's what wires up RefreshDatabase

        // RefreshDatabase migrates the schema but does NOT run seeders
        // automatically. Roles/permissions must exist before assignRole()
        // works, so we seed once per test — this mirrors what
        // migrate:fresh --seed does for your real database.
        Artisan::call('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    public function test_guest_is_redirected_away_from_categories(): void
    {
        $response = $this->get('/categories');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_categories(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/categories');

        $response->assertOk(); // HTTP 200
    }

    public function test_non_admin_cannot_create_a_category(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/categories/create');

        $response->assertForbidden(); // HTTP 403
    }

    public function test_admin_can_create_a_category(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/categories/create');

        $response->assertOk();
    }

    public function test_staff_cannot_access_manager_staff_page(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole('staff');

        $response = $this->actingAs($staff)->get('/manager/staff');

        $response->assertForbidden();
    }

    public function test_manager_can_access_manager_staff_page(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $response = $this->actingAs($manager)->get('/manager/staff');

        $response->assertOk();
    }
}