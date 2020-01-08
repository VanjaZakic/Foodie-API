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
}
