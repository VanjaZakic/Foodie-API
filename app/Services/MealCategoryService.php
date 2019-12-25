<?php

namespace App\Services;

use App\Company;
use App\Http\Requests\MealCategoryRequest;
use App\MealCategory;
use App\Repositories\MealCategoryRepository;
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
     */
    public function showAll($company)
    {
        $this->repository = MealCategory::where('company_id', $company->id)->get();
        return $this->repository->all();
    }

    /**
     * @param MealCategoryRequest $request
     * @param Company $company
     * @return mixed
     * @throws ValidatorException
     */
    public function store($request, $company)
    {
        return $this->repository->create([
            'name'       => $request->name,
            'image'      => $request->image,
            'company_id' => $company->id
        ]);
    }

    /**
     * @param MealCategory $mealCategory
     * @return mixed
     */
    public function show($mealCategory)
    {
        return $this->repository->find($mealCategory);
    }

    /**
     * @param MealCategory $mealCategory
     * @param MealCategoryRequest $request
     * @return mixed
     */
    public function update($mealCategory, $request)
    {
        return $this->repository->find($mealCategory)->first()->fill($request->all())->save();
    }

    /**
     * @param MealCategory $mealCategory
     * @return mixed
     */
    public function destroy($mealCategory)
    {
        return $this->repository->find($mealCategory)->each->delete();
    }
}
