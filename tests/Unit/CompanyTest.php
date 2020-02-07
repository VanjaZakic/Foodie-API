<?php

namespace Tests\Unit;

use App\Company;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class UserTest
 * @package Tests\Unit
 */
class CompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_belongs_to_company()
    {
        $user = factory(User::class)->states(USER::ROLE_PRODUCER_ADMIN)->create();

        $this->assertInstanceOf(Company::class, $user->company);
    }

    public function test_it_has_many_users()
    {
        $company = factory(Company::class)->states(COMPANY::TYPE_PRODUCER)->create();
        $user    = factory(User::class)->states(USER::ROLE_PRODUCER_USER)->create(['company_id' => $company->id]);

        $this->assertTrue($company->users->contains($user));
    }
}
