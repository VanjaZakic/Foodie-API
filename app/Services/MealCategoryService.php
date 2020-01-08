<?php

namespace App\Services;

use App\Company;
use App\Criteria\MealCategoryCriteria;
use App\Http\Requests\MealCategoryRequest;
use App\Repositories\MealCategoryRepository;
use Illuminate\Support\Facades\Auth;
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
    protected $repository;

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
        $this->repository->pushCriteria(new MealCategoryCriteria($company));
        return $this->repository->all();
    }

    /**
     * @param MealCategoryRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function store($request)
    {
        return $this->repository->create([
            'name'       => $request->name,
            'image'      => $request->image,
            'company_id' => Auth::user()->company_id
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
