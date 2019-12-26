<?php

namespace App\Services;

use App\Http\Requests\StoreCompanyRequest;
use App\Repositories\CompanyRepository;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class CompanyService
 * @package App\Services
 */
class CompanyService
{
    /**
     * @var CompanyRepository
     */
    protected $repository;

    /**
     * UserService constructor.
     *
     * @param CompanyRepository $repository
     */
    public function __construct(CompanyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $limit
     *
     * @return mixed
     */
    public function getPaginated($limit)
    {
        return $this->repository->scopeQuery(function ($query) {
            return $query->where('type', 'producer');
        })->paginate($limit);
    }

    /**
     * @param StoreCompanyRequest $request
     *
     * @return mixed
     * @throws ValidatorException
     */
    public function store(StoreCompanyRequest $request)
    {
        return $this->repository->create($request->all());
    }

    /**
     * @param $request
     * @param $companyId
     *
     * @return mixed
     * @throws ValidatorException
     */
    public function update($request, $companyId)
    {
        return $this->repository->update(
            $request->all(), $companyId);
    }

    /**
     * @param $companyId
     *
     * @return int
     */
    public function softDelete($companyId)
    {
        return $this->repository->delete($companyId);
    }
}
