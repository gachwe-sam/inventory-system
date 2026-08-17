<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubCategoryRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_subcategories_index_route_is_available_for_authenticated_users(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/subcategories');

        $response->assertOk();
    }
}
