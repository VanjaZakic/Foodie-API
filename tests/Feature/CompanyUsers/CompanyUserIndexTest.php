<?php

namespace Tests\Feature\CompanyUsers;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyUserIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_producer_admin_can_see_self_company_users()
    {
        $producer_admin = factory(User::class)->states(User::ROLE_PRODUCER_ADMIN)->create();
        $producer_users = factory(User::class, 2)->states(User::ROLE_PRODUCER_USER)->create([
            'company_id' => $producer_admin->company_id
        ]);

        $response = $this->actingAs($producer_admin)->json('GET', "api/v1/companies/{$producer_admin->company_id}/users");

        $producer_users->each(function ($producer_user) use ($response) {
            $response->assertJsonFragment([
                'id' => $producer_user->id
            ]);
        });
    }

    public function test_customer_admin_can_see_self_company_users()
    {
        $customer_admin = factory(User::class)->states(User::ROLE_CUSTOMER_ADMIN)->create();
        $customer_users = factory(User::class, 2)->states(User::ROLE_CUSTOMER_USER)->create([
            'company_id' => $customer_admin->company_id
        ]);

        $response = $this->actingAs($customer_admin)->json('GET', "api/v1/companies/{$customer_admin->company_id}/users");

        $customer_users->each(function ($customer_user) use ($response) {
            $response->assertJsonFragment([
                'id' => $customer_user->id
            ]);
        });
    }
}
