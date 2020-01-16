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
     *
     * @param User $authUser
     */
    public function __construct(User $authUser)
    {
        $this->authUser = $authUser;
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
            $input->role == $user->role &&
            $input->company_id == $user->company_id;
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
            $input->first_name == $user->first_name &&
            $input->last_name == $user->last_name &&
            $input->phone == $user->phone &&
            $input->address == $user->address &&
            $input->email == $user->email &&
            $input->role == User::ROLE_USER &&
            $input->company_id == null;
    }
}
