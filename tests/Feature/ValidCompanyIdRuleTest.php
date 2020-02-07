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
        $producerCompanyDb = factory(Company::class)->states(COMPANY::TYPE_PRODUCER)->make(['id' => 1]);
        $producerAdminDb   = factory(User::class)->states(User::ROLE_PRODUCER_ADMIN)->make([
            'company_id' => $producerCompanyDb->id,
            'id'         => 1
        ]);
        $producerAdmin     = factory(User::class)->states(User::ROLE_PRODUCER_ADMIN)->make(['id' => 2]);

        $request           = $this->mock_request($producerAdmin, $producerAdminDb, $this->admin);
        $userRepository    = $this->mock_company_admin($request, $producerAdminDb);
        $companyRepository = $this->mock_company($producerCompanyDb);

        $validCompanyIdRule = $this->getValidCompanyIdRule($request, $userRepository, $companyRepository, $producerCompanyDb);
        $this->assertFalse($validCompanyIdRule->passes('company_id', $producerAdmin->company_id));
    }

    public function test_valid_company_type_for_company_users()
    {
        $customerCompanyDb = factory(Company::class)->states(COMPANY::TYPE_CUSTOMER)->make(['id' => 1]);
        $producerAdminDb   = factory(User::class)->states(User::ROLE_PRODUCER_ADMIN)->make();
        $producerUser      = factory(User::class)->states(User::ROLE_PRODUCER_USER)->make();

        $request           = $this->mock_request($producerUser, $customerCompanyDb, $this->admin);
        $companyRepository = $this->mock_company($customerCompanyDb);
        $userRepository    = $this->mock_company_admin($request, $producerAdminDb);

        $validCompanyIdRule = $this->getValidCompanyIdRule($request, $userRepository, $companyRepository, $customerCompanyDb);
        $this->assertFalse($validCompanyIdRule->passes('company_id', $producerUser->company_id));
    }

    /**
     * @param $user
     * @param $userDb
     * @param $admin
     *
     * @return MockInterface
     */
    private function mock_request($user, $userDb, $admin)
    {
        return $this->mock(Request::class, function ($mock) use ($user, $userDb, $admin) {
            $mock->shouldReceive('all')->andReturn([
                'id'         => $user->id,
                'role'       => $user->role,
                'company_id' => $userDb->company_id,
            ]);
            $mock->shouldReceive('user')->andReturn($admin);
        });
    }

    /**
     * @param $request
     * @param $companyAdminDb
     *
     * @return MockInterface
     */
    private function mock_company_admin($request, $companyAdminDb)
    {
        return $this->mock(UserRepository::class, function ($mock) use ($request, $companyAdminDb) {
            $mock->shouldReceive('findWhere')->with([
                'role'       => $request->role,
                'company_id' => $companyAdminDb->company_id,
                ['id', '!=', $request->id]
            ])->andReturn([$companyAdminDb]);
        });
    }

    /**
     * @param $companyDb
     *
     * @return MockInterface
     */
    private function mock_company($companyDb)
    {
        return $this->mock(CompanyRepository::class, function ($mock) use ($companyDb) {
            $mock->shouldReceive('find')->with($companyDb->id)->andReturn($companyDb);
        });
    }

    /**
     * @param $request
     * @param $userRepository
     * @param $companyRepository
     * @param $companyDb
     *
     * @return ValidCompanyIdRule
     */
    private function getValidCompanyIdRule($request, $userRepository, $companyRepository, $companyDb)
    {
        $validCompanyIdRule = new ValidCompanyIdRule($request, $userRepository, $companyRepository);
        $validCompanyIdRule->setCompanyId($companyDb->id);
        return $validCompanyIdRule;
    }
}
