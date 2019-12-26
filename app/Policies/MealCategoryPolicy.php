<?php

namespace App\Policies;

use App\Company;
use App\MealCategory;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MealCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create meal categories.
     *
     * @param User $user
     * @param Company $company
     * @return mixed
     */
    public function create(User $user, Company $company)
    {
        return $company->id === $user->company_id;
    }

    /**
     * Determine whether the user can update the meal category.
     *
     * @param User $user
     * @param MealCategory $mealCategory
     * @return mixed
     */
    public function update(User $user, MealCategory $mealCategory)
    {
        return $mealCategory->company_id === $user->company_id;
    }

    /**
     * Determine whether the user can delete the meal category.
     *
     * @param User $user
     * @param MealCategory $mealCategory
     * @return mixed
     */
    public function delete(User $user, MealCategory $mealCategory)
    {
        return $mealCategory->company_id === $user->company_id;
    }
}
