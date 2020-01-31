<?php

namespace App\Policies;

use App\Company;
use App\Permissions\AdminPermission;
use App\Permissions\CompanyAdminsPermission;
use App\Permissions\PermissionFactory;
use App\Permissions\UsersPermission;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

/**
 * Class CompanyPolicy
 * @package App\Policies
 */
class CompanyPolicy
{
    use HandlesAuthorization;
    /**
     * @var Request
     */
    private Request $request;
    /**
     * @var AdminPermission|CompanyAdminsPermission|UsersPermission|bool
     */
    private $permission;

    /**
     * CompanyPolicy constructor.
     *
     * @param PermissionFactory $permissionFactory
     * @param Request           $request
     */
    public function __construct(PermissionFactory $permissionFactory, Request $request)
    {
        $this->permission = $permissionFactory->getPermission($request->user());
        $this->request    = $request;
    }

    /**
     * @param User    $user
     * @param Company $company
     *
     * @return bool
     */
    public function view(User $user, Company $company)
    {
        return $user->company_id === $company->id;
    }

    /**
     * @param User    $authUser
     * @param Company $company
     *
     * @return bool
     */
    public function index(User $authUser, Company $company)
    {
        return $this->permission->canViewCompanyUsers($company);
    }
}
