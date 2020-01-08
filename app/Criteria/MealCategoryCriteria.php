<?php

namespace App\Criteria;

use App\MealCategory;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class MealCategoryCriteria
 * @package
 */
class MealCategoryCriteria implements CriteriaInterface
{
    /**
     * @var MealCategory
     */
    protected $mealCategory;

    /**
     * MealCategoryCriteria constructor
     *
     * @param MealCategory $mealCategory
     */
    public function __construct(MealCategory $mealCategory)
    {
        $this->mealCategory = $mealCategory;
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
        return $model->where('meal_category_id', '=', $this->mealCategory->id);
    }
}
