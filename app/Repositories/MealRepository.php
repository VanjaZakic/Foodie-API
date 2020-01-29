<?php

namespace App\Repositories;

use App\Meal;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class MealRepository
 * @package App\Repositories
 */
class MealRepository extends BaseRepository
{
    /**
     * @var Meal
     */
    protected $mealModel;

    /**
     * MealRepository constructor
     *
     * @param Application $app
     * @param Meal $mealModel
     */
    public function __construct(Application $app, Meal $mealModel)
    {
        parent::__construct($app);
        $this->mealModel = $mealModel;
    }

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
     */
    public function countIds($companyId, $mealIds)
    {
        $count = $this->mealModel->join('meal_categories', 'meal_categories.id', '=', 'meals.meal_category_id')
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
        $meals = $this->mealModel->whereIn('id', $mealIds)->get();

        return $meals;
    }
}
