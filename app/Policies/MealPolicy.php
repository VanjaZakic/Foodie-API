<?php

namespace App\Policies;

use App\Meal;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class MealPolicy
 * @package App\Policies
 */
class MealPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update or delete the meal.
     *
     * @param User $user
     * @param Meal $meal
     * @return bool
     */
    public function ifCompanyId(User $user, Meal $meal)
    {
        return $meal->mealCategory->company_id === $user->company_id;
    }
}
