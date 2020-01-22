<?php

namespace App\Permissions;

use App\Company;
use App\User;

/**
 * Class AdminPermission
 * @package App\Permissions
 */
class AdminPermission implements Permissionable
{
    /**
     * @var User $authUser
     */
    private $authUser;

    /**
     * AdminPermission constructor.
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
        return true;
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
            !($this->authUser->id == $user->id &&
                ($input->role != $user->role || $input->company_id));
    }

    /**
     * @param Company $company
     *
     * @return bool
     */
    public function canViewCompanyUsers(Company $company)
    {
        return true;
    }
}
