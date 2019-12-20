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
    public function store(MealRequest $request)
    {
        return $this->repository->create($request->all());
    }

    /**
     * @param Meal $id
     * @return mixed
     */
    public function show(Meal $id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param Meal $id
     * @param MealRequest $request
     * @return mixed
     */
    public function update(Meal $id, MealRequest $request)
    {
        return $this->repository->find($id)->first()->fill($request->all())->save();
    }

    /**
     * @param Meal $id
     * @return mixed
     */
    public function destroy(Meal $id)
    {
        return $this->repository->find($id)->each->delete();
    }
}
