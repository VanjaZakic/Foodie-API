<?php

namespace App\Permissions;

use App\User;

/**
 * Class CompanyAdminsPermission
 * @package App\Permissions
 */
class CompanyAdminsPermission implements Permissionable
{
    /**
     * @var User $authUser
     */
    private $authUser;

    /**
     * CompanyAdminsPermission constructor.
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
        return $this->authUser->id == $user->id || $this->authUser->company_id == $user->company_id;
    }

    /**
     * @param $user
     * @param $input
     *
     * @return bool
     */
    public function canUpdate($user, $input)
    {
        return $this->canUpdateSelf($user, $input) || $this->canUpdateUser($user, $input);
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

    /**
     * @param $user
     * @param $input
     *
     * @return bool
     */
    private function canUpdateUser($user, $input)
    {
        return
            $this->authUser->company_id == $user->company_id &&
            $input['role'] == User::ROLE_USER &&
            $input['company_id'] == null;
    }
}
