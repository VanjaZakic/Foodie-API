<?php

namespace App\Services;

use App\Http\Requests\MealRequest;
use App\Meal;
use App\Repositories\MealRepository;

/**
 * Class MealService
 * @package App\Services
 */
class MealService
{
    /**
     * @var MealRepository
     */
    protected $repository;

    /**
     * MealService constructor.
     *
     * @param MealRepository $repository
     */
    public function __construct(MealRepository $repository)
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
     * @param MealRequest $request
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store($request)
    {
        return $this->repository->create($request->all());
    }

    /**
     * @param Meal $meal
     * @return mixed
     */
    public function show($meal)
    {
        return $this->repository->find($meal);
    }

    /**
     * @param Meal $meal
     * @param MealRequest $request
     * @return mixed
     */
    public function update($meal, $request)
    {
        return $this->repository->find($meal)->first()->fill($request->all())->save();
    }

    /**
     * @param Meal $meal
     * @return mixed
     */
    public function destroy($meal)
    {
        return $this->repository->find($meal)->each->delete();
    }
}
