<?php

namespace Tests\Feature;

use App\Company;
use App\Repositories\CompanyRepository;
use App\Repositories\UserRepository;
use App\Rules\ValidCompanyIdRule;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * Class ValidCompanyIdRuleTest
 * @package Tests\Feature
 */
class ValidCompanyIdRuleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var mixed
     */
    protected $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = factory(User::class)->states(USER::ROLE_ADMIN)->create();
    }

    public function test_if_company_admin_already_exists()
    {
        $producer_company_db = factory(Company::class)->states(COMPANY::TYPE_PRODUCER)->make(['id' => 1]);
        $producer_admin_db   = factory(User::class)->states(User::ROLE_PRODUCER_ADMIN)->make([
            'company_id' => $producer_company_db->id,
            'id'         => 1
        ]);
        $producer_admin      = factory(User::class)->states(User::ROLE_PRODUCER_ADMIN)->make(['id' => 2]);

        $request           = $this->mock_request($producer_admin, $producer_admin_db, $this->admin);
        $userRepository    = $this->mock_company_admin($request, $producer_admin_db);
        $companyRepository = $this->mock_company($producer_company_db);

        $validCompanyIdRule = $this->getValidCompanyIdRule($request, $userRepository, $companyRepository, $producer_company_db);
        $this->assertFalse($validCompanyIdRule->passes('company_id', $producer_admin->company_id));
    }

    public function test_valid_company_type_for_company_users()
    {
        $customer_company_db = factory(Company::class)->states(COMPANY::TYPE_CUSTOMER)->make(['id' => 1]);
        $producer_admin_db   = factory(User::class)->states(User::ROLE_PRODUCER_ADMIN)->make();
        $producer_user       = factory(User::class)->states(User::ROLE_PRODUCER_USER)->make();

        $request           = $this->mock_request($producer_user, $customer_company_db, $this->admin);
        $companyRepository = $this->mock_company($customer_company_db);
        $userRepository    = $this->mock_company_admin($request, $producer_admin_db);

        $validCompanyIdRule = $this->getValidCompanyIdRule($request, $userRepository, $companyRepository, $customer_company_db);
        $this->assertFalse($validCompanyIdRule->passes('company_id', $producer_user->company_id));
    }

    /**
     * @param $user
     * @param $user_db
     * @param $admin
     *
     * @return MockInterface
     */
    private function mock_request($user, $user_db, $admin)
    {
        return $this->mock(Request::class, function ($mock) use ($user, $user_db, $admin) {
            $mock->shouldReceive('all')->andReturn([
                'id'         => $user->id,
                'role'       => $user->role,
                'company_id' => $user_db->company_id,
            ]);
            $mock->shouldReceive('user')->andReturn($admin);
        });
    }

    /**
     * @param $request
     * @param $company_admin_db
     *
     * @return MockInterface
     */
    private function mock_company_admin($request, $company_admin_db)
    {
        return $this->mock(UserRepository::class, function ($mock) use ($request, $company_admin_db) {
            $mock->shouldReceive('findWhere')->with([
                'role'       => $request->role,
                'company_id' => $company_admin_db->company_id,
                ['id', '!=', $request->id]
            ])->andReturn([$company_admin_db]);
        });
    }

    /**
     * @param $company_db
     *
     * @return MockInterface
     */
    private function mock_company($company_db)
    {
        return $this->mock(CompanyRepository::class, function ($mock) use ($company_db) {
            $mock->shouldReceive('find')->with($company_db->id)->andReturn($company_db);
        });
    }

    /**
     * @param $request
     * @param $userRepository
     * @param $companyRepository
     * @param $company_db
     *
     * @return ValidCompanyIdRule
     */
    private function getValidCompanyIdRule($request, $userRepository, $companyRepository, $company_db)
    {
        $validCompanyIdRule = new ValidCompanyIdRule($request, $userRepository, $companyRepository);
        $validCompanyIdRule->setCompanyId($company_db->id);
        return $validCompanyIdRule;
    }
}
