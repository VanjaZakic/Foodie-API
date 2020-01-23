<?php

namespace Tests\Feature\Companies;

use App\Company;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class CompanyIndexTest
 * @package Tests\Feature\Companies
 */
class CompanyIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_see_producer_companies()
    {
        $roles     = User::$roles;
        $companies = factory(Company::class, 2)->states(COMPANY::TYPE_PRODUCER)->create();

        foreach ($roles as $role) {
            $user = factory(User::class)->create([
                'role' => $role,
            ]);

            $response = $this->actingAs($user)->json('GET', 'api/v1/companies');

            $companies->each(function ($company) use ($response) {
                $response->assertJsonFragment([
                    'id' => $company->id
                ]);
            });
        }
    }

    public function test_it_returns_a_collection_of_producer_companies()
    {
        $admin     = factory(User::class)->states(USER::ROLE_ADMIN)->create();
        $companies = factory(Company::class, 2)->states(COMPANY::TYPE_PRODUCER)->create();

        $response = $this->actingAs($admin)->json('GET', 'api/v1/companies');

        $companies->each(function ($company) use ($response) {
            $response->assertJsonFragment([
                'id' => $company->id
            ]);
        });
    }

    public function test_it_does_not_return_customer_companies()
    {
        $admin     = factory(User::class)->states(USER::ROLE_ADMIN)->create();
        $companies = factory(Company::class, 2)->states(COMPANY::TYPE_CUSTOMER)->create();

        $response = $this->actingAs($admin)->json('GET', 'api/v1/companies');

        $companies->each(function ($company) use ($response) {
            $response->assertJsonMissing([
                'id' => $company->id
            ]);
        });
    }

    public function test_it_has_paginated_data()
    {
        $admin = factory(User::class)->states(USER::ROLE_ADMIN)->create();

        $this->actingAs($admin)->json('GET', 'api/v1/companies')
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
