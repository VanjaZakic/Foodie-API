<?php

namespace App\Services;

use App\Criteria\ProducerCompaniesCriteria;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Repositories\CompanyRepository;
use Prettus\Repository\Exceptions\RepositoryException;
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
     * @throws RepositoryException
     */
    public function getPaginated($limit = null)
    {
        $this->repository->pushCriteria(new ProducerCompaniesCriteria());
        return $this->repository->paginate($limit);
    }

    /**
     * @param CompanyStoreRequest $request
     *
     * @return mixed
     * @throws ValidatorException
     */
    public function store(CompanyStoreRequest $request)
    {
        $path = $request->file('image')->store('images', 'public');

        return $this->repository->create([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
            'email'   => $request->email,
            'type'    => $request->type,
            'image'   => $path,
            'lat'     => $request->lat,
            'lng'     => $request->lng
        ]);
    }

    /**
     * @param $request
     * @param $companyId
     *
     * @return mixed
     * @throws ValidatorException
     */
    public function update(CompanyUpdateRequest $request, $companyId)
    {
        $path = $request->file('image')->store('images', 'public');

        return $this->repository->update([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
            'email'   => $request->email,
            'type'    => $request->type,
            'image'   => $path,
            'lat'     => $request->lat,
            'lng'     => $request->lng],
            $companyId);
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
