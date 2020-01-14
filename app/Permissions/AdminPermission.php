<?php

namespace App\Permissions;

/**
 * Class AdminPermission
 * @package App\Permissions
 */
class AdminPermission implements IPermission
{
    /**
     * @var mixed
     */
    private $authUser;

    /**
     * AdminPermission constructor.
     */
    public function __construct()
    {
        $this->authUser = request()->user();
    }

    /**
     * @param $user
     *
     * @return bool
     */
    public function canView($user)
    {
        return true;
    }

    /**
     * @param $user
     * @param $input
     *
     * @return bool
     */
    public function canUpdate($user, $input)
    {
        return $this->canUpdateUser($user, $input);
    }

    /**
     * @param $user
     * @param $input
     *
     * @return bool
     */
    private function canUpdateUser($user, $input)
    {
        return
            !($this->authUser->id == $user->id &&
                ($input['role'] != $user->role || $input['company_id'] != null));
    }
}
