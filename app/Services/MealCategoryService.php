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
    public function store(MealCategoryRequest $request)
    {
        return $this->repository->create($request->all());
    }

    /**
     * @param MealCategory $id
     * @return mixed
     */
    public function show(MealCategory $id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param MealCategory $id
     * @param MealCategoryRequest $request
     * @return mixed
     */
    public function update(MealCategory $id, MealCategoryRequest $request)
    {
        return $this->repository->find($id)->first()->fill($request->all())->save();
    }

    /**
     * @param MealCategory $id
     * @return mixed
     */
    public function destroy(MealCategory $id)
    {
        return $this->repository->find($id)->each->delete();
    }
}
