<?php

namespace App\Policies;

use App\MealCategory;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class MealCategoryPolicy
 * @package App\Policies
 */
class MealCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update or delete the meal category.
     *
     * @param User         $user
     * @param MealCategory $mealCategory
     *
     * @return bool
     */
    public function ifCompanyId(User $user, MealCategory $mealCategory)
    {
        return $mealCategory->company_id === $user->company_id;
    }
}
