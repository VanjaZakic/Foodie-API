<?php

namespace App\Services;

use App\Company;
use App\Criteria\CompanyCriteria;
use App\Exceptions\AdminUserForCompanyAlreadyExistsException;
use App\Exceptions\InvalidUserRoleForCompanyException;
use App\Http\Requests\CompanyUserStoreRequest;
use App\User;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\UserRepository;

/**
 * Class UserService
 * @package App\Services
 */
class CompanyUserService
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * UserService constructor.
     *
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $company
     * @param $limit
     *
     * @return mixed
     * @throws RepositoryException
     */
    public function getPaginated($limit = null, $company)
    {
        $this->repository->pushCriteria(new CompanyCriteria($company));
        return $this->repository->paginate($limit);
    }

    /**
     * @param CompanyUserStoreRequest $request
     * @param                         $company
     *
     * @return mixed
     * @throws ValidatorException
     * @throws AdminUserForCompanyAlreadyExistsException
     * @throws InvalidUserRoleForCompanyException
     */
    public function store($request, $company)
    {
        $user = $this->repository->findWhere(['company_id' => $company->id, 'role' => $request->role]);

        if (count($user) != 0) {
            throw new AdminUserForCompanyAlreadyExistsException();
        }

        if (!$this->isCompanyTypeCompatible($request, $company)) {
            throw new InvalidUserRoleForCompanyException();
        }
        
        return $this->repository->create(array_merge($request->all(), ['company_id' => $company->id]));
    }

    /**
     * @param $request
     * @param $company
     *
     * @return bool
     */
    private function isCompanyTypeCompatible($request, $company)
    {
        return (($company->type == Company::TYPE_PRODUCER && $request->role == User::ROLE_PRODUCER_ADMIN) ||
            ($company->type == Company::TYPE_CUSTOMER && $request->role == User::ROLE_CUSTOMER_ADMIN));
    }

}
