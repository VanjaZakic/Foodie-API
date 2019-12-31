<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class MealCriteria
 * @package
 */
class MealCriteria implements CriteriaInterface
{
    /**
     * @var int
     */
    protected $mealCategoryId;

    /**
     * MealCriteria constructor
     *
     * @param int $mealCategoryId
     */
    public function __construct(int $mealCategoryId)
    {
        $this->mealCategoryId = $mealCategoryId;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->where('meal_category_id', '=', $this->mealCategoryId);
        return $model;
    }
}
