<?php

namespace App\Policies;

use App\Company;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class CompanyPolicy
 * @package App\Policies
 */
class CompanyPolicy
{
    use HandlesAuthorization;

    /**
     * @param User    $user
     * @param Company $company
     *
     * @return bool
     */
    public function view(User $user, Company $company)
    {
        return $user->role == USER::ROLE_ADMIN || $user->company_id === $company->id;
    }
}
