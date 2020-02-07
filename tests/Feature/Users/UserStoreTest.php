<?php

namespace Tests\Feature\Users;

use App\Company;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class UserStoreTest
 * @package Tests\Feature
 */
class UserStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_requires_data()
    {
        $this->json('POST', 'api/v1/users')
            ->assertJsonValidationErrors(['first_name', 'last_name', 'phone', 'address', 'email', 'password', 'role']);
    }

    public function test_it_returns_validation_error_if_over_max_length()
    {
        $this->json('POST', 'api/v1/users', [
            'first_name' => 'firstnamefirstnamefirstnamefirstnamefirstnamefirstnamefirstnamefirstnamefirstnamefirstname',
            'last_name'  => 'lastnamelastnamelastnamelastnamelastnamelastnamelastnamelastnamelastnamelastnamelastname',
            'phone'      => '012345678901234567890123456789',
            'email'      => 'emailemailemailemailemailemailemailemailemailemailemailemailemailemailemailemailemail@gmail.com'
        ])
            ->assertJsonValidationErrors(['first_name', 'last_name', 'phone', 'email']);
    }

    public function test_it_requires_unique_email_and_phone()
    {
        $admin = factory(User::class)->states(USER::ROLE_ADMIN)->create();

        $this->json('POST', 'api/v1/users', [
            'email' => $admin->email,
            'phone' => $admin->phone
        ])
            ->assertJsonValidationErrors(['email', 'phone']);
    }

    public function test_it_returns_validation_error_if_not_valid_role()
    {
        $roles = [User::ROLE_ADMIN, User::ROLE_PRODUCER_ADMIN, User::ROLE_CUSTOMER_ADMIN, 'WrongRole', ''];

        foreach ($roles as $role) {
            $this->json('POST', 'api/v1/users', [
                'role' => $role
            ])
                ->assertJsonValidationErrors(['role']);
        }
    }

    public function test_it_returns_validation_error_if_company_id_is_required()
    {
        $roles = [User::ROLE_PRODUCER_USER, User::ROLE_CUSTOMER_USER];

        foreach ($roles as $role) {
            $this->json('POST', 'api/v1/users', [
                'role' => $role
            ])
                ->assertJsonValidationErrors(['company_id']);
        }
    }

    public function test_it_stores_a_user()
    {
        $user = factory(User::class)->create($params = $this->getParams());

        $this->assertDatabaseHas('users', $this->getDbParams($params));
    }

    public function test_it_stores_a_producer_user()
    {
        $user = factory(User::class)->create($params = $this->getParams([
            'role'       => User::ROLE_PRODUCER_USER,
            'company_id' => $company_id = (factory(Company::class)->states('producer')->create())->id
        ]));

        $this->assertDatabaseHas('users', $this->getDbParams($params));
    }

    public function test_it_stores_a_customer_user()
    {
        $user = factory(User::class)->create($params = $this->getParams([
            'role'       => User::ROLE_CUSTOMER_USER,
            'company_id' => $company_id = (factory(Company::class)->states(COMPANY::TYPE_CUSTOMER)->create())->id
        ]));

        $this->assertDatabaseHas('users', $this->getDbParams($params));
    }

    private function getParams(...$difference)
    {
        $params = [
            'first_name' => 'firstname',
            'last_name'  => 'lastname',
            'phone'      => '123456789',
            'password'   => '123456',
            'address'    => 'address',
            'role'       => User::ROLE_USER,
            'company_id' => null
        ];
        $params = array_merge($params, ...$difference);

        return $params;
    }

    private function getDbParams($params)
    {
        unset($params['password']);
        return $params;
    }
}
