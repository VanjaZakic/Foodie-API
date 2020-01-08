<?php

namespace App\Services;

use App\Criteria\CompanyUsersCriteria;
use App\Http\Requests\StoreCompanyUserRequest;
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
    public function getPaginated($company, $limit)
    {
        $this->repository->pushCriteria(new CompanyUsersCriteria($company));
        return $this->repository->paginate($limit);
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
