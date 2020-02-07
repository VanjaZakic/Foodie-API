<?php

namespace App\Services;

use App\Company;
use App\Criteria\CompanyCriteria;
use App\Http\Requests\MealCategoryRequest;
use App\Repositories\MealCategoryRepository;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class MealCategoryService
 * @package App\Services
 */
class MealCategoryService
{
    /**
     * @var MealCategoryRepository
     */
    protected MealCategoryRepository $repository;

    /**
     * MealCategoryService constructor.
     *
     * @param MealCategoryRepository $repository
     */
    public function __construct(MealCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Company $company
     * @return mixed
     * @throws RepositoryException
     */
    public function showAll($company)
    {
        $this->repository->pushCriteria(new CompanyCriteria($company));
        return $this->repository->all();
    }

    /**
     * @param MealCategoryRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function store($request)
    {
        $path = $request->file('image')->store('public/images');

        return $this->repository->create([
            'name'       => $request->name,
            'image'      => $path,
            'company_id' => $request->user()->company_id
        ]);
    }

    /**
     * @param int $mealCategoryId
     * @param MealCategoryRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function update($request, $mealCategoryId)
    {
        return $this->repository->update($request->all(), $mealCategoryId);
    }

    /**
     * @param int $mealCategoryId
     * @return mixed
     */
    public function destroy($mealCategoryId)
    {
        return $this->repository->delete($mealCategoryId);
    }
}
