<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class UserShowTest
 * @package Tests\Feature
 */
class UserShowTest extends TestCase

{
    use RefreshDatabase;

    public function test_it_fails_if_a_user_cant_be_found()
    {
        $admin = factory(User::class)->states('admin')->create();

        $this->actingAs($admin)->json('GET', '/api/v1/users/nouser')
            ->assertStatus(404);
    }

    public function test_user_can_see_self()
    {
        $roles = User::$roles;
        foreach ($roles as $role) {
            $user = factory(User::class)->states($role)->create();
        }

        $this->actingAs($user)->json('GET', "api/v1/users/{$user->id}")
            ->assertJsonFragment([
                'id' => $user->id
            ]);
    }

    public function test_admin_can_see_users()
    {
        $admin = factory(User::Class)->states('admin')->create();

        $roles = USER::$roles;
        foreach ($roles as $role) {
            $user = factory(User::class)->states($role)->create();

            $this->actingAs($admin)->json('GET', "api/v1/users/{$user->id}")
                ->assertJsonFragment([
                    'id' => $user->id
                ]);
        }
    }

    public function test_company_admins_can_see_company_users()
    {
        $producer_admin = factory(User::class)->states('producer_admin')->create();
        $producer_user  = factory(User::class)->states('producer_user')->create([
            'company_id' => $producer_admin->company_id
        ]);

        $this->actingAs($producer_admin)->json('GET', "api/v1/users/{$producer_user->id}")
            ->assertJsonFragment([
                'id' => $producer_user->id
            ]);
    }

    public function test_company_admins_cant_see_other_users()
    {
        $producer_admin = factory(User::class)->states('producer_admin')->create();

        $roles = [User::ROLE_ADMIN, User::ROLE_CUSTOMER_ADMIN, User::ROLE_PRODUCER_USER, User::ROLE_CUSTOMER_USER, User::ROLE_USER];
        foreach ($roles as $role) {
            $user = factory(User::class)->states($role)->create();

            $this->actingAs($producer_admin)->json('GET', "api/v1/users/{$user->id}")
                ->assertStatus(403);
        }
    }

    public function test_users_cant_see_other_users()
    {
        $authUser = factory(User::class)->states('user')->create();

        $roles = $this->rolesWithoutSelf($authUser->role);
        foreach ($roles as $role) {
            $user = factory(User::class)->states($role)->create();

            $this->actingAs($authUser)->json('GET', "api/v1/users/{$user->id}")
                ->assertStatus(403);
        }
    }

    public static function rolesWithoutSelf($role)
    {
        $roles = User::$roles;
        $key   = array_search($role, $roles);
        unset($roles[$key]);
        return $roles;
    }
}
