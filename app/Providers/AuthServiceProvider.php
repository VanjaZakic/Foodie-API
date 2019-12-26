<?php

namespace App\Providers;

use App\Company;
use App\Meal;
use App\MealCategory;
use App\Policies\MealCategoryPolicy;
use App\Policies\MealPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

/**
 * Class AuthServiceProvider
 * @package App\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Meal::class         => MealPolicy::class,
        MealCategory::class => MealCategoryPolicy::class,
        Company::class      => MealCategoryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
    }
}
