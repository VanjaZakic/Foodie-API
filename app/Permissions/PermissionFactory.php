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
     * @var mixed
     */
    private $authUser;

    /**
     * PermissionFactory constructor.
     */
    public function __construct()
    {
        $this->authUser = request()->user();
    }

    /**
     * @return AdminPermission|CompanyAdminsPermission|UsersPermission|bool
     */
    public function getPermission()
    {
        switch ($this->authUser->role) {
            case User::ROLE_ADMIN:
                return new AdminPermission();
            case User::ROLE_PRODUCER_ADMIN:
            case User::ROLE_CUSTOMER_ADMIN:
                return new CompanyAdminsPermission();
            case User::ROLE_PRODUCER_USER:
            case User::ROLE_CUSTOMER_USER:
            case User::ROLE_USER:
                return new UsersPermission();
            default:
                return false;
        }
    }
}
