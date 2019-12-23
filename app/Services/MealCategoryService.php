<?php

namespace App\Services;

use App\Http\Requests\MealCategoryRequest;
use App\MealCategory;
use App\Repositories\MealCategoryRepository;

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
     * @return mixed
     */
    public function showAll()
    {
        return $this->repository->all();
    }

    /**
     * @param MealCategoryRequest $request
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store($request)
    {
        return $this->repository->create($request->all());
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
