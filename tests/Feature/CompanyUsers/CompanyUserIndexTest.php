<?php

namespace Tests\Feature\CompanyUsers;

use App\Company;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class CompanyUserIndexTest
 * @package Tests\Feature\CompanyUsers
 */
class CompanyUserIndexTest extends TestCase
{
    use RefreshDatabase;


    public function test_admin_can_see_company_users()
    {
        $admin = factory(User::class)->states(USER::ROLE_ADMIN)->create();

        $roles = [USER::ROLE_PRODUCER_ADMIN, USER::ROLE_CUSTOMER_ADMIN];
        foreach ($roles as $role) {
            $company_admin     = factory(User::class)->states($role)->create();
            $company_user_role = $role == USER::ROLE_PRODUCER_ADMIN ? USER::ROLE_PRODUCER_USER : USER::ROLE_CUSTOMER_USER;
            $company_user      = factory(User::class)->states($company_user_role)->create([
                'company_id' => $company_admin->company_id
            ]);
        }

        $this->actingAs($admin)
            ->json('GET', "api/v1/companies/{$company_admin->company_id}/users")
            ->assertJsonFragment([
                'id' => $company_user->id
            ]);
    }
    
    public function test_company_admins_can_see_self_company_users()
    {
        $roles = [USER::ROLE_PRODUCER_ADMIN, USER::ROLE_CUSTOMER_ADMIN];

        foreach ($roles as $role) {
            $company_admin      = factory(User::class)->states($role)->create();
            $company_users_role = $company_admin->role == USER::ROLE_PRODUCER_ADMIN ? USER::ROLE_PRODUCER_USER : USER::ROLE_CUSTOMER_USER;
            $company_users      = factory(User::class, 2)->states($company_users_role)->create([
                'company_id' => $company_admin->company_id
            ]);
        }

        $response = $this->actingAs($company_admin)->json('GET', "api/v1/companies/{$company_admin->company_id}/users");

        $company_users->each(function ($company_user) use ($response) {
            $response->assertJsonFragment([
                'id' => $company_user->id
            ]);
        });
    }

    public function test_it_is_unauthorized_for_users()
    {
        $company = factory(Company::class)->states(COMPANY::TYPE_PRODUCER)->create();
        $roles   = [USER::ROLE_PRODUCER_USER, USER::ROLE_CUSTOMER_USER, USER::ROLE_USER];

        foreach ($roles as $role) {
            $user = factory(User::class)->create();

            $this->actingAs($user)->json('GET', "api/v1/companies/{$company->id}/users")
                ->assertStatus(403);
        }
    }

    public function test_it_has_paginated_data()
    {
        $producer_admin = factory(User::class)->states(USER::ROLE_PRODUCER_ADMIN)->create();

        $this->actingAs($producer_admin)->json('GET', "api/v1/companies/{$producer_admin->company_id}/users")
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
