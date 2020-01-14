<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_shows_unauthorized_if_not_admin()
    {
        $rolesWithoutAdmin = User::rolesWithoutAdmin();

        foreach ($rolesWithoutAdmin as $role) {
            $this->do_test_it_shows_unauthorized_if_not_admin($role);
        }
    }

    private function do_test_it_shows_unauthorized_if_not_admin($role)
    {
        $user = factory(User::class)->create([
            'role' => $role,
        ]);

        $this->actingAs($user)
            ->json('GET', 'api/v1/users')
            ->assertStatus(401);
    }

    public function test_it_returns_a_collection_of_users()
    {
        $admin = factory(User::class)->states('admin')->create();
        $users = factory(User::class, 2)->states('producer_admin')->create();

        $response = $this->actingAs($admin)->json('GET', 'api/v1/users');

        $users->each(function ($user) use ($response) {
            $response->assertJsonFragment([
                'id' => $user->id
            ]);
        });
    }

    public function test_it_has_paginated_data()
    {
        $admin = factory(User::class)->states('admin')->create();

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
