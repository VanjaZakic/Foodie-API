<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use App\MealCategory;

/**
 * Class MealCategoryRepository
 * @package App\Repositories
 */
class MealCategoryRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MealCategory::class;
    }
}
