<?php

namespace App\Permissions;

use App\User;

interface IPermission
{
    public function canView(User $user);

    public function canUpdate(User $user, $input);
}
