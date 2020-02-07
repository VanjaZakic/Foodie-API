<?php

namespace App\Services;

use App\Criteria\CompanyCriteria;
use App\Http\Requests\CompanyUserStoreRequest;
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
    protected UserRepository $repository;

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
     */
    public function store($request, $company)
    {
        return $this->repository->create(array_merge($request->all(), ['company_id' => $company->id]));
    }
}
