<?php

namespace App\Services;

use App\Criteria\MealCriteria;
use App\Http\Requests\MealRequest;
use App\MealCategory;
use App\Repositories\MealRepository;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

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
     * @param MealCategory $mealCategory
     * @return mixed
     * @throws RepositoryException
     */
    public function showAll(MealCategory $mealCategory)
    {
        $this->repository->pushCriteria(new MealCriteria($mealCategory));
        return $this->repository->all();
    }

    /**
     * @param MealRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function store($request)
    {
        return $this->repository->create($request->all());
    }

    /**
     * @param int $mealId
     * @param MealRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function update($request, $mealId)
    {
        return $this->repository->update($request->all(), $mealId);
    }

    /**
     * @param int $mealId
     * @return mixed
     */
    public function destroy($mealId)
    {
        return $this->repository->delete($mealId);
    }
}
