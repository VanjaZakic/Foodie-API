<?php

namespace Tests\Feature\Users;

use App\Company;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class UserUpdateTest
 * @package Tests\Feature
 */
class UserUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        //$this->withoutExceptionHandling();
    }

    public function test_it_requires_data()
    {
        $admin = factory(User::class)->states(USER::ROLE_ADMIN)->create();

        $this->actingAs($admin)->json('PUT', "api/v1/users/{$admin->id}")
            ->assertJsonValidationErrors(['first_name', 'last_name', 'phone', 'address', 'email', 'role']);
    }

    public function test_it_returns_validation_error_if_over_max_length()
    {
        $admin = factory(User::class)->states(USER::ROLE_ADMIN)->create();

        $this->actingAs($admin)->json('PUT', "api/v1/users/{$admin->id}", [
            'first_name' => 'firstnamefirstnamefirstnamefirstnamefirstnamefirstnamefirstnamefirstnamefirstnamefirstname',
            'last_name'  => 'lastnamelastnamelastnamelastnamelastnamelastnamelastnamelastnamelastnamelastnamelastname',
            'phone'      => '012345678901234567890123456789',
            'email'      => 'emailemailemailemailemailemailemailemailemailemailemailemailemailemailemailemailemail@gmail.com'
        ])
            ->assertJsonValidationErrors(['first_name', 'last_name', 'phone', 'email']);
    }

    public function test_it_requires_unique_email_and_phone()
    {
        $user1 = factory(User::class)->states(USER::ROLE_USER)->create();
        $user2 = factory(User::class)->states(USER::ROLE_USER)->create();

        $this->actingAs($user1)->json('PUT', "api/v1/users/{$user1->id}",
            $this->getParams($user1, [
                'phone' => $user2->phone,
                'email' => $user2->email,
            ]))
            ->assertJsonValidationErrors(['email', 'phone']);
    }

    public function test_it_returns_validation_error_if_not_valid_role()
    {
        $user = factory(User::class)->states('user')->create();

        $this->actingAs($user)->json('PUT', "api/v1/users/{$user->id}",
            $this->getParams($user, [
                'role' => 'wrongRole',
            ]))
            ->assertJsonValidationErrors(['role']);
    }

    public function test_user_can_update_self()
    {
        $roles = User::$roles;
        foreach ($roles as $role) {
            $user = factory(User::class)->states($role)->create();
            $this->actingAs($user)->json('PUT', "api/v1/users/{$user->id}", $params = $this->getNewParams($user));
            $this->assertDatabaseHas('users', $this->getNewParams($user, $params));
        }
    }

    public function test_users_cant_update_self_role()
    {
        $roles = User::availableRoles(User::ROLE_ADMIN);
        foreach ($roles as $role) {
            $user = factory(User::class)->states($role)->create();
            $role == User::ROLE_USER ? $newRole = User::ROLE_ADMIN : $newRole = User::ROLE_USER;
            $this->actingAs($user)->json('PUT', "api/v1/users/{$user->id}", $this->getNewParams($user, [
                "role"       => $newRole,
                "company_id" => null
            ]))
                ->assertStatus(403);
        }
    }

    public function test_users_cant_update_self_company_id()
    {
        $roles = User::$roles;
        foreach ($roles as $role) {
            $user = factory(User::class)->states($role)->create();
            $this->actingAs($user)->json('PUT', "api/v1/users/{$user->id}", $this->getNewParams($user, [
                "company_id" => 'InvalidCompanyId'
            ]))
                ->assertStatus(403);
        }
    }

    public function test_producer_admin_can_downgrade_self_producer_user()
    {
        $producer_admin = factory(User::class)->states(USER::ROLE_PRODUCER_ADMIN)->create();
        $producer_user  = factory(User::class)->states(USER::ROLE_PRODUCER_USER)->create([
            'company_id' => $producer_admin->company_id
        ]);
        $this->actingAs($producer_admin)->json('PUT', "api/v1/users/{$producer_user->id}", $params = $this->getParams($producer_user, [
            "id"         => $producer_user->id,
            "role"       => USER::ROLE_USER,
            "company_id" => null
        ]));

        $this->assertDatabaseHas('users', $params);
    }

    public function test_producer_admin_cant_update_other_users()
    {
        $producer_admin = factory(User::class)->states(USER::ROLE_PRODUCER_ADMIN)->create();

        $roles = User::$roles;

        foreach ($roles as $role) {
            $user = factory(User::class)->states($role)->create();
            $this->actingAs($producer_admin)->json('PUT', "api/v1/users/{$user->id}", $this->getNewParams($user, [
                "role"       => USER::ROLE_USER,
                "company_id" => null
            ]))
                ->assertStatus(403);
        }
    }

    public function test_admin_can_update_other_users()
    {
        $admin = factory(User::class)->states(USER::ROLE_ADMIN)->create();

        $roles = User::availableRoles(USER::ROLE_ADMIN);
        foreach ($roles as $role) {
            $user = factory(User::class)->states($role)->create();
            $this->actingAs($admin)->json('PUT', "api/v1/users/{$user->id}", $params = $this->getNewParams($user, [
                "role"       => User::ROLE_USER,
                "company_id" => null
            ]));

            $this->assertDatabaseHas('users', $params);
        }
    }

    public function test_it_returns_validation_error_if_company_id_is_incompatible_with_user_role()
    {
        $admin   = factory(User::class)->states(USER::ROLE_ADMIN)->create();
        $company = factory(Company::class)->states(COMPANY::TYPE_CUSTOMER)->create();

        $roles = [USER::ROLE_PRODUCER_ADMIN, USER::ROLE_PRODUCER_USER];

        foreach ($roles as $role) {
            $user = factory(User::class)->states($role)->create();

            $this->actingAs($admin)->json('PUT', "api/v1/users/{$user->id}", $this->getParams($user, [
                "id"         => $user->id,
                "company_id" => $company->id
            ]))
                ->assertJsonValidationErrors(['company_id']);
        }
    }

    public function test_it_returns_validation_error_if_producer_admin_for_company_already_exists()
    {
        $admin           = factory(User::class)->states(USER::ROLE_ADMIN)->create();
        $producer_admin1 = factory(User::class)->states(USER::ROLE_PRODUCER_ADMIN)->create();
        $producer_admin2 = factory(User::class)->states(USER::ROLE_PRODUCER_ADMIN)->create();

        $this->actingAs($admin)->json('PUT', "api/v1/users/{$producer_admin1->id}", $this->getParams($producer_admin1, [
            "company_id" => $producer_admin2->company_id
        ]))
            ->assertJsonValidationErrors(['company_id']);
    }

    public function test_it_fails_if_a_user_cant_be_found()
    {
        $admin = factory(User::class)->states(USER::ROLE_ADMIN)->create();

        $this->actingAs($admin)->json('PUT', '/api/v1/users/nouser')
            ->assertStatus(404);
    }

    public function test_it_fails_if_a_company_cant_be_found()
    {
        $admin          = factory(User::class)->states(USER::ROLE_ADMIN)->create();
        $producer_admin = factory(User::class)->states(USER::ROLE_PRODUCER_ADMIN)->create();

        $this->actingAs($admin)->json('PUT', "api/v1/users/{$producer_admin->id}", $this->getParams($producer_admin, [
            "company_id" => "invalidCompanyId"
        ]))
            ->assertStatus(404);
    }

    private function getNewParams($user, ...$difference)
    {
        $params = [
            "id"         => $user->id,
            "first_name" => "newFirstName",
            "last_name"  => "newLastName",
            "phone"      => rand(111111111, 999999999),
            "address"    => "newAddress",
            "email"      => "{$user->role}@gmail.com",
            "role"       => $user->role,
            "company_id" => $user->company_id
        ];
        $params = array_merge($params, ...$difference);

        return $params;
    }

    private function getParams($user, ...$difference)
    {
        $params = [
            'id'         => $user->id,
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'phone'      => $user->phone,
            'address'    => $user->address,
            'email'      => $user->email,
            'role'       => $user->role,
            'company_id' => $user->company_id
        ];
        $params = array_merge($params, ...$difference);

        return $params;
    }
}
