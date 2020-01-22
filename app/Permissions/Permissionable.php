<?php

namespace App\Permissions;

use App\Company;
use App\User;

/**
 * Interface Permissionable
 * @package App\Permissions
 */
interface Permissionable
{
    /**
     * @param User $user
     *
     * @return mixed
     */
    public function canViewUser(User $user);

    /**
     * @param User $user
     * @param      $input
     *
     * @return mixed
     */
    public function canUpdateUser(User $user, $input);

    /**
     * @param Company $company
     *
     * @return mixed
     */
    public function canViewCompanyUsers(Company $company);
}
