<?php

namespace App\Permissions;

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
    public function canView(User $user);

    /**
     * @param User $user
     * @param      $input
     *
     * @return mixed
     */
    public function canUpdate(User $user, $input);
}
