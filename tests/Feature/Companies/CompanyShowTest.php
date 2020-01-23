<?php

namespace Tests\Feature\Users;

use App\Company;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class CompanyShowTest
 * @package Tests\Feature
 */
class CompanyShowTest extends TestCase

{
    use RefreshDatabase;

    public function test_it_fails_if_company_cant_be_found()
    {
        $admin = factory(User::class)->states(USER::ROLE_ADMIN)->create();

        $this->actingAs($admin)->json('GET', '/api/v1/companies/nocompany')
            ->assertStatus(404);
    }

    public function test_a_user_can_see_company()
    {
        $company = factory(Company::class)->states(COMPANY::TYPE_PRODUCER)->create();

        $roles = USER::$roles;
        foreach ($roles as $role) {
            $user = factory(User::class)->states($role)->create();

            $this->actingAs($user)->json('GET', "api/v1/companies/{$company->id}")
                ->assertJsonFragment([
                    'id' => $company->id
                ]);
        }
    }
}
