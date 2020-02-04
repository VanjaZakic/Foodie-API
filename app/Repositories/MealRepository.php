<?php

namespace App\Repositories;

use App\Meal;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class MealRepository
 * @package App\Repositories
 */
class MealRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Meal::class;
    }

    /**
     * @param $companyId
     * @param $mealIds
     * @return int
     * @throws RepositoryException
     */
    public function countIds($companyId, $mealIds)
    {
        $count = $this->makeModel()->join('meal_categories', 'meal_categories.id', '=', 'meals.meal_category_id')
            ->where('meal_categories.company_id', $companyId)
            ->whereIn('meals.id', $mealIds)
            ->count();

        return $count;
    }
}
