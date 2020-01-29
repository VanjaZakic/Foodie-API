<?php

namespace Tests\Feature\CompanyUsers;

use App\Company;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class CompanyUserStoreTest
 * @package Tests\Feature\CompanyUsers
 */
class CompanyUserStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User $admin
     */
    protected $admin;
    private $producerCompany;
    private $customerCompany;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin           = factory(User::class)->states(USER::ROLE_ADMIN)->create();
        $this->producerCompany = factory(Company::class)->states(COMPANY::TYPE_PRODUCER)->create();
        $this->customerCompany = factory(Company::class)->states(COMPANY::TYPE_CUSTOMER)->create();
    }

    public function test_it_requires_data()
    {
        $this->actingAs($this->admin)->json('POST', "api/v1/companies/{$this->producerCompany->id}/users")
            ->assertJsonValidationErrors(['first_name', 'last_name', 'phone', 'address', 'email', 'password', 'role']);
    }

    public function test_it_returns_validation_error_if_over_max_length()
    {
        $this->actingAs($this->admin)->json('POST', "api/v1/companies/{$this->producerCompany->id}/users", [
            'first_name' => 'firstnamefirstnamefirstnamefirstnamefirstnamefirstnamefirstnamefirstnamefirstnamefirstname',
            'last_name'  => 'lastnamelastnamelastnamelastnamelastnamelastnamelastnamelastnamelastnamelastnamelastname',
            'phone'      => '012345678901234567890123456789',
            'email'      => 'emailemailemailemailemailemailemailemailemailemailemailemailemailemailemailemailemail@gmail.com'
        ])
            ->assertJsonValidationErrors(['first_name', 'last_name', 'phone', 'email']);
    }

    public function test_it_requires_unique_email_and_phone()
    {
        $this->actingAs($this->admin)->json('POST', "api/v1/companies/{$this->producerCompany->id}/users",
            $this->getParams(User::ROLE_PRODUCER_ADMIN, $this->producerCompany->id, [
                'phone' => $this->admin->phone,
                'email' => $this->admin->email,
            ]))
            ->assertJsonValidationErrors(['email', 'phone']);
    }

    public function test_it_returns_validation_error_if_not_valid_role()
    {
        $roles = [User::ROLE_ADMIN, User::ROLE_PRODUCER_USER, User::ROLE_CUSTOMER_USER, 'WrongRole', ''];

        foreach ($roles as $role) {
            $this->actingAs($this->admin)->json('POST', "api/v1/companies/{$this->producerCompany->id}/users", [
                'role' => $role
            ])
                ->assertJsonValidationErrors(['role']);
        }
    }

    public function test_it_stores_a_company_admin()
    {
        $roles = [USER::ROLE_PRODUCER_ADMIN, USER::ROLE_CUSTOMER_ADMIN];
        foreach ($roles as $role) {
            $company = $role == USER::ROLE_PRODUCER_ADMIN ? $this->producerCompany : $this->customerCompany;
            $this->actingAs($this->admin)->json('POST', "api/v1/companies/{$company->id}/users",
                $params = $this->getParams($role, $company->id));

            unset($params['password']);
            unset($params['password_confirmation']);
            $this->assertDatabaseHas('users', $params);
        }
    }

    public function test_it_returns_validation_error_if_company_id_is_incompatible_with_user_role()
    {
        $roles = [USER::ROLE_PRODUCER_ADMIN, USER::ROLE_CUSTOMER_ADMIN];

        foreach ($roles as $role) {
            $company = $role == USER::ROLE_PRODUCER_ADMIN ? $this->customerCompany : $this->producerCompany;
            $this->actingAs($this->admin)->json('POST', "api/v1/companies/{$company->id}/users",
                $this->getParams($role, $company->id))
                ->assertJsonValidationErrors(['company_id']);
        }
    }

    public function test_it_returns_validation_error_if_admin_for_company_already_exists()
    {
        $roles = [USER::ROLE_PRODUCER_ADMIN, USER::ROLE_CUSTOMER_ADMIN];

        foreach ($roles as $role) {
            $company_admin = factory(User::class)->states($role)->create();

            $this->actingAs($this->admin)->json('POST', "api/v1/companies/{$company_admin->company_id}/users",
                $this->getParams($role, $company_admin->company_id))
                ->assertJsonValidationErrors(['company_id']);
        }
    }

    public function test_it_fails_if_a_company_cant_be_found()
    {
        $this->actingAs($this->admin)->json('POST', "api/v1/companies/noCompany/users")
            ->assertStatus(404);
    }

    private function getParams($role, $companyId, ...$difference)
    {
        $params = [
            'first_name'            => 'firstname',
            'last_name'             => 'lastname',
            'phone'                 => rand(111111111, 999999999),
            'password'              => '123456',
            'password_confirmation' => '123456',
            'address'               => 'address',
            'email'                 => "{$role}@gmail.com",
            'role'                  => $role,
            'company_id'            => $companyId
        ];
        $params = array_merge($params, ...$difference);

        return $params;
    }
}
