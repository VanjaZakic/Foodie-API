<?php

namespace App\Rules;

use App\Repositories\CompanyRepository;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Contracts\Validation\ImplicitRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

/**
 * Class ValidCompanyId
 * @package App\Rules
 */
class ValidCompanyIdRule implements Rule, ImplicitRule
{
    /**
     * @var
     */
    protected $input;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var CompanyRepository
     */
    private $companyRepository;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var
     */
    public $companyId;


    /**
     * ValidCompanyIdRule constructor.
     *
     * @param Request           $request
     * @param UserRepository    $userRepository
     * @param CompanyRepository $companyRepository
     */
    public function __construct(Request $request, UserRepository $userRepository, CompanyRepository $companyRepository)
    {
        $this->request           = $request;
        $this->userRepository    = $userRepository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * @param $companyId
     *
     * @return $this
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->request->user()->role == User::ROLE_ADMIN) {
            switch ($this->request->role) {
                case User::ROLE_ADMIN:
                case User::ROLE_USER:
                    return !($value != null);
                case User::ROLE_PRODUCER_USER:
                case User::ROLE_CUSTOMER_USER:
                    return $this->validCompanyIdForCompanyUsers();
                case User::ROLE_PRODUCER_ADMIN:
                case User::ROLE_CUSTOMER_ADMIN:
                    return $this->validCompanyIdForCompanyAdmins();
                default:
                    return true;
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    private function validCompanyIdForCompanyUsers()
    {
        $company = $this->findCompany($this->companyId);
        return $this->isCompanyTypeCompatibleWithUserRole($this->request->role, $company->type);
    }

    /**
     * @return bool
     */
    private function validCompanyIdForCompanyAdmins()
    {
        $company      = $this->findCompany($this->companyId);
        $companyAdmin = $this->findCompanyAdmin($this->request->role, $this->companyId);

        return $this->isCompanyTypeCompatibleWithUserRole($this->request->role, $company->type) && !count($companyAdmin);
    }

    /**
     * @param $companyId
     *
     * @return mixed
     */
    private function findCompany($companyId)
    {
        return $this->companyRepository->find($companyId);
    }

    /**
     * @param $userRole
     * @param $companyId
     *
     * @return mixed
     */
    private function findCompanyAdmin($userRole, $companyId)
    {
        return $this->userRepository->findWhere([
            'role'       => $userRole,
            'company_id' => $companyId,
            ['id', '!=', $this->request->id]
        ]);
    }

    /**
     * @param $userRole
     * @param $companyType
     *
     * @return bool
     */
    private function isCompanyTypeCompatibleWithUserRole($userRole, $companyType)
    {
        return strpos($userRole, $companyType) !== false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid company id.';
    }
}
