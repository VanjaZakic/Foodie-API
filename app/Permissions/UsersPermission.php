<?php

namespace App\Permissions;

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
    private $authUser;

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
    public function canView($user)
    {
        return $this->authUser->id == $user->id;
    }

    /**
     * @param User   $user
     * @param object $input
     *
     * @return bool
     */
    public function canUpdate($user, $input)
    {
        return $this->canUpdateSelf($user, $input);
    }

    /**
     * @param User   $user
     * @param object $input
     *
     * @return bool
     */
    private function canUpdateSelf($user, $input)
    {
        return
            $this->authUser->id == $user->id &&
            $input->role == $user->role &&
            $input->company_id == $user->company_id;
    }
}
