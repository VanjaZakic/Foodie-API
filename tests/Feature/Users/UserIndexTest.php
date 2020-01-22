<?php

namespace Tests\Feature\Users;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class UserIndexTest
 * @package Tests\Feature
 */
class UserIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_shows_unauthorized_if_not_admin()
    {
        $roles = User::availableRoles(USER::ROLE_ADMIN);

        foreach ($roles as $role) {
            $user = factory(User::class)->create([
                'role' => $role,
            ]);

            $this->actingAs($user)
                ->json('GET', 'api/v1/users')
                ->assertStatus(403);
        }
    }

    public function test_it_returns_a_collection_of_users()
    {
        $admin = factory(User::class)->states(USER::ROLE_ADMIN)->create();
        $users = factory(User::class, 2)->states(USER::ROLE_PRODUCER_ADMIN)->create();

        $response = $this->actingAs($admin)->json('GET', 'api/v1/users');

        $users->each(function ($user) use ($response) {
            $response->assertJsonFragment([
                'id' => $user->id
            ]);
        });
    }

    public function test_it_has_paginated_data()
    {
        $admin = factory(User::class)->states(USER::ROLE_ADMIN)->create();

        $this->actingAs($admin)->json('GET', 'api/v1/users')
            ->assertJsonStructure([
                'meta' => [
                    'pagination' => [
                        'count',
                        'links'
                    ]
                ]
            ]);
    }
}
