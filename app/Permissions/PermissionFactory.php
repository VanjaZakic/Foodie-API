<?php

namespace App\Permissions;

use App\User;

/**
 * Class PermissionFactory
 * @package App\Permissions
 */
class PermissionFactory
{
    /**
     * @param $authUser
     *
     * @return AdminPermission|CompanyAdminsPermission|UsersPermission|bool
     */
    public function getPermission(User $authUser)
    {
        switch ($authUser->role) {
            case User::ROLE_ADMIN:
                return new AdminPermission($authUser);
            case User::ROLE_PRODUCER_ADMIN:
            case User::ROLE_CUSTOMER_ADMIN:
                return new CompanyAdminsPermission($authUser);
            case User::ROLE_PRODUCER_USER:
            case User::ROLE_CUSTOMER_USER:
            case User::ROLE_USER:
                return new UsersPermission($authUser);
            default:
                return false;
        }
    }
}
