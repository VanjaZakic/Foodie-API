<?php

namespace App\Services;

use App\Http\Requests\MealCategoryRequest;
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
     * @param MealCategoryRequest $request
     *
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function save(MealCategoryRequest $request)
    {
        return $mealCategory = $this->repository->create($request->all());
    }
}
