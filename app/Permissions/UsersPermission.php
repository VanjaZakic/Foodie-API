<?php

namespace App\Permissions;

use App\Company;
use App\User;

/**
 * Class UsersPermission
 * @package App\Permissions
 */
class UsersPermission implements Permissionable
{
    /**
     * @var User $authUser
     */
    private User $authUser;

    /**
     * UsersPermission constructor.
     *
     * @param User $authUser
     */
    public function __construct(User $authUser)
    {
        $this->authUser = $authUser;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function canViewUser($user)
    {
        return $this->authUser->id == $user->id;
    }

    /**
     * @param User   $user
     * @param object $input
     *
     * @return bool
     */
    public function canUpdateUser($user, $input)
    {
        return
            $this->authUser->id == $user->id &&
            $input->role == $user->role &&
            $input->company_id == $user->company_id;
    }

    /**
     * @param Company $company
     *
     * @return bool
     */
    public function canViewCompanyUsers(Company $company)
    {
        return false;
    }
}
