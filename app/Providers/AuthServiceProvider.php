<?php

namespace App\Providers;

use App\Company;
use App\Meal;
use App\MealCategory;
use App\Order;
use App\Policies\CompanyPolicy;
use App\Policies\MealCategoryPolicy;
use App\Policies\MealPolicy;
use App\Policies\OrderPolicy;
use App\Policies\UserPolicy;
use App\User;
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
    protected array $policies = [
        Meal::class         => MealPolicy::class,
        MealCategory::class => MealCategoryPolicy::class,
        Company::class      => CompanyPolicy::class,
        Order::class        => OrderPolicy::class,
        User::class         => UserPolicy::class,
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
