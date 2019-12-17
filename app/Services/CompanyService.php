<?php


namespace App\Services;


use App\Http\Requests\CompanyRequest;
use App\Repositories\CompanyRepository;

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
     * @param CompanyRequest $request
     *
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function save(CompanyRequest $request)
    {
        return $company = $this->repository->create($request->all());
    }
}
