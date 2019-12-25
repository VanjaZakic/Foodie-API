<?php

namespace App\Services;

use App\Http\Requests\StoreCompanyUserRequest;
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
     * @param $companyId
     *
     * @return mixed
     */
    public function getAll($companyId)
    {
        return $this->repository->scopeQuery(function ($query) use ($companyId) {
            return $query->where('company_id', $companyId);
        });
    }

    /**
     * @param StoreCompanyUserRequest $request
     *
     * @param                         $company
     *
     * @return mixed
     * @throws ValidatorException
     */
    public function store($request, $company)
    {
        $user = $this->repository->findByField('company_id', $company->id);

        if (!count($user)) {
            $request = array_merge($request->all(), ['company_id' => $company->id]);
            return $this->repository->create($request);
        }

        return null;
    }
}
