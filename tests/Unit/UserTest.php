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
class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_hashes_password()
    {
        $user = factory(User::class)->states('admin')->create([
            'password' => '123456'
        ]);

        $this->assertNotEquals($user->password, '123456');
    }

    public function test_it_belongs_to_company()
    {
        $user = factory(User::class)->states('producer_admin')->create();

        $this->assertInstanceOf(Company::class, $user->company);
    }
}
