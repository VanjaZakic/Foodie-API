<?php

namespace App\Repositories;

use App\Meal;
use Prettus\Repository\Eloquent\BaseRepository;

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
     * @return mixed
     */
    public function countIds($companyId, $mealIds)
    {
        $count = Meal::join('meal_categories', 'meal_categories.id', '=', 'meals.meal_category_id')
            ->where('meal_categories.company_id', $companyId)
            ->whereIn('meals.id', $mealIds)
            ->count();

        return $count;
    }

    /**
     * @param $mealIds
     * @return mixed
     */
    public function getMeals($mealIds)
    {
        $meals = Meal::whereIn('id', $mealIds)->get();

        return $meals;
    }
}
