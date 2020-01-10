<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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

    public function test_it_requires_unique_email()
    {
        $user = factory(User::class)->create([
            'password' => '123456',
            'role'     => User::ROLE_ADMIN
        ]);

        $this->json('POST', 'api/v1/users', [
            'email' => $user->email
        ])
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_returns_validation_error_if_not_valid_role()
    {
        $roles = [User::ROLE_ADMIN, User::ROLE_PRODUCER_ADMIN, User::ROLE_CUSTOMER_ADMIN, 'WrongRole', ''];

        foreach ($roles as $role) {
            $this->do_test_it_returns_validation_error_if_not_valid_role($role);
        }
    }

    public function do_test_it_returns_validation_error_if_not_valid_role($role)
    {
        $this->json('POST', 'api/v1/users', [
            'role' => $role
        ])
            ->assertJsonValidationErrors(['role']);
    }

    public function test_it_returns_validation_error_if_company_id_is_required()
    {
        $roles = [User::ROLE_PRODUCER_USER, User::ROLE_CUSTOMER_USER];

        foreach ($roles as $role) {
            $this->do_test_it_returns_validation_error_if_company_id_is_required($role);
        }
    }

    public function do_test_it_returns_validation_error_if_company_id_is_required($role)
    {
        $this->json('POST', 'api/v1/users', [
            'role' => $role
        ])
            ->assertJsonValidationErrors(['company_id']);
    }

//    public function test_it_stores_a_user()
//    {
//        $roles = [User::ROLE_PRODUCER_USER, User::ROLE_CUSTOMER_USER, User::ROLE_USER];
//
//        foreach ($roles as $role) {
//            $this->do_test_it_stores_a_user($role);
//        }
//    }

//    public function do_test_it_stores_a_user($role)
//    {
//        $user = factory(User::class)->create([
//            'first_name' => $firstname = 'firstname',
//            'last_name'  => $lastname = 'lastname',
//            'phone' => $phone = '123456789',
//            'password'  => '123456',
//            'role'      => $role,
//        ]);
//    }
}
