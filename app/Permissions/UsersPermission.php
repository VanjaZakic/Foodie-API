<?php

namespace App\Permissions;

/**
 * Class UsersPermission
 * @package App\Permissions
 */
class UsersPermission implements IPermission
{
    /**
     * @var mixed
     */
    private $authUser;

    /**
     * UsersPermission constructor.
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
        return $this->authUser->id == $user->id;
    }

    /**
     * @param $user
     * @param $input
     *
     * @return bool
     */
    public function canUpdate($user, $input)
    {
        return $this->canUpdateSelf($user, $input);
    }

    /**
     * @param $user
     * @param $input
     *
     * @return bool
     */
    private function canUpdateSelf($user, $input)
    {
        return
            $this->authUser->id == $user->id &&
            $input['role'] == $user->role &&
            $input['company_id'] == $user->company_id;
    }
}
