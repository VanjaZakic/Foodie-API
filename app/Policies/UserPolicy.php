<?php

namespace App\Policies;

use App\Http\Requests\UpdateUserRequest;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param User $authUser
     * @param User $user
     *
     * @return mixed
     */
    public function view(User $authUser, User $user)
    {
        return $authUser->id == $user->id ||
            ($authUser->role == User::ROLE_PRODUCER_ADMIN && $authUser->company_id == $user->company_id) ||
            ($authUser->role == User::ROLE_CUSTOMER_ADMIN && $authUser->company_id == $user->company_id);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User              $authUser
     * @param User              $user
     * @param UpdateUserRequest $request
     *
     * @return mixed
     */
    public function update(User $authUser, User $user, UpdateUserRequest $request)
    {
        switch ($authUser->role) {
            case User::ROLE_ADMIN:
                return $authUser->canAdminUpdateUser($user, $request->all());
            case User::ROLE_PRODUCER_ADMIN:
                return $authUser->canProducerAdminUpdateUser($user, $request->all());
            case User::ROLE_CUSTOMER_ADMIN:
                return $authUser->canCustomerAdminUpdateUser($user, $request->all());
            case User::ROLE_PRODUCER_USER:
                return $authUser->canProducerUserUpdateUser($user, $request->all());
            case User::ROLE_CUSTOMER_USER:
                return $authUser->canCustomerUserUpdateUser($user, $request->all());
            case User::ROLE_USER:
                return $authUser->canUserUpdateUser($user, $request->all());
            default:
                return false;
        }
    }
}
