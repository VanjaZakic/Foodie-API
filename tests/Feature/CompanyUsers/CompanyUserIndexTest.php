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
            $companyAdmin    = factory(User::class)->states($role)->create();
            $companyUserRole = $role == USER::ROLE_PRODUCER_ADMIN ? USER::ROLE_PRODUCER_USER : USER::ROLE_CUSTOMER_USER;
            $companyUser     = factory(User::class)->states($companyUserRole)->create([
                'company_id' => $companyAdmin->company_id
            ]);
        }

        $this->actingAs($admin)
            ->json('GET', "api/v1/companies/{$companyAdmin->company_id}/users")
            ->assertJsonFragment([
                'id' => $companyUser->id
            ]);
    }

    public function test_company_admins_can_see_self_company_users()
    {
        $roles = [USER::ROLE_PRODUCER_ADMIN, USER::ROLE_CUSTOMER_ADMIN];

        foreach ($roles as $role) {
            $companyAdmin     = factory(User::class)->states($role)->create();
            $companyUsersRole = $companyAdmin->role == USER::ROLE_PRODUCER_ADMIN ? USER::ROLE_PRODUCER_USER : USER::ROLE_CUSTOMER_USER;
            $companyUsers     = factory(User::class, 2)->states($companyUsersRole)->create([
                'company_id' => $companyAdmin->company_id
            ]);
        }

        $response = $this->actingAs($companyAdmin)->json('GET', "api/v1/companies/{$companyAdmin->company_id}/users");

        $companyUsers->each(function ($companyUser) use ($response) {
            $response->assertJsonFragment([
                'id' => $companyUser->id
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
        $producerAdmin = factory(User::class)->states(USER::ROLE_PRODUCER_ADMIN)->create();

        $this->actingAs($producerAdmin)->json('GET', "api/v1/companies/{$producerAdmin->company_id}/users")
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
