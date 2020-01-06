<?php

namespace App\Rules;

use App\Company;
use App\Repositories\CompanyRepository;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Contracts\Validation\ImplicitRule;
use Illuminate\Contracts\Validation\Rule;

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
     * ValidCompanyIdRule constructor.
     *
     * @param UserRepository $userRepository
     * @param CompanyRepository $companyRepository
     */
    public function __construct(UserRepository $userRepository, CompanyRepository $companyRepository)
    {
        $this->userRepository = $userRepository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * @param $input
     *
     * @return ValidCompanyIdRule
     */
    public function setRequest($input)
    {
        $this->input = $input;
        return $this;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (auth()->user()->role == User::ROLE_ADMIN) {

            if ($this->input['company_id'] != null) {
                $company = $this->companyRepository->find($this->input['company_id']);
            }

            if ($this->input['role'] == User::ROLE_ADMIN) {
                if ($value != null) {
                    return false;
                }
            }

            if ($this->input['role'] == User::ROLE_PRODUCER_ADMIN) {
                $producer_admin = $this->userRepository->findWhere([
                    'company_id' => $this->input['company_id'],
                    'role'       => User::ROLE_PRODUCER_ADMIN,
                    ['id', '!=', $this->input['id']]
                ]);

                if (!$company || $company->type != Company::TYPE_PRODUCER || count($producer_admin)) {
                    return false;
                }
            }

            if ($this->input['role'] == User::ROLE_CUSTOMER_ADMIN) {
                $customer_admin = $this->userRepository->findWhere([
                    'company_id' => $this->input['company_id'],
                    'role'       => User::ROLE_CUSTOMER_ADMIN,
                    ['id', '!=', $this->input['id']]
                ]);

                if (!$company || $company->type != Company::TYPE_CUSTOMER || count($customer_admin)) {
                    return false;
                }
            }

            if ($this->input['role'] == User::ROLE_PRODUCER_USER) {
                if (!$company || $company->type != Company::TYPE_PRODUCER) {
                    return false;
                }
            }

            if ($this->input['role'] == User::ROLE_CUSTOMER_USER) {
                if (!$company || $company->type != Company::TYPE_CUSTOMER) {
                    return false;
                }
            }

            if ($this->input['role'] == User::ROLE_USER) {
                if ($value != null) {
                    return false;
                }
            }

        }

        return true;
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
