<?php

namespace Tests\Feature\CompanyUsers;

use App\Company;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyUserIndexTest extends TestCase
{
    use RefreshDatabase;

//    public function test_it_shows_unauthorized_if_not_admin()
//    {
//        $company = factory(Company::class)->create();
//        $roles   = User::availableRoles(USER::ROLE_ADMIN);
//
//        foreach ($roles as $role) {
//            $user = factory(User::class)->create([
//                'role' => $role,
//            ]);
//
//            $this->actingAs($user)
//                ->json('GET', "api/v1/companies/{$company->id}/users")
//                ->assertStatus(401);
//        }
//    }
}
