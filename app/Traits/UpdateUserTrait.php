<?php

namespace App\Traits;

use App\User;

/**
 * Trait UpdateUserTrait
 * @package App\Traits
 */
trait UpdateUserTrait
{
    /**
     * @param User $user
     * @param $input
     *
     * @return bool
     */
    public function canAdminUpdateUser(User $user, $input)
    {
        if ($this->canAdminUpdateHimself($user, $input)) {
            return false;
        }

        return true;
    }

    /**
     * @param User $user
     * @param $input
     *
     * @return bool
     */
    public function canProducerAdminUpdateUser(User $user, $input)
    {
        return ($this->isUserUpdatingHimself($user, $input) || $this->isProducerAdminUpdatingProducerUser($user, $input));
    }

    /**
     * @param User $user
     * @param $input
     *
     * @return bool
     */
    public function canCustomerAdminUpdateUser(User $user, $input)
    {
        return ($this->isUserUpdatingHimself($user, $input) || $this->isCustomerAdminUpdatingCustomerUser($user, $input));
    }

    /**
     * @param User $user
     * @param $input
     *
     * @return bool
     */
    public function canProducerUserUpdateUser(User $user, $input)
    {
        return $this->isUserUpdatingHimself($user, $input);
    }

    /**
     * @param User $user
     * @param $input
     *
     * @return bool
     */
    public function canCustomerUserUpdateUser(User $user, $input)
    {
        return $this->isUserUpdatingHimself($user, $input);
    }

    /**
     * @param User $user
     * @param $input
     *
     * @return bool
     */
    public function canUserUpdateUser(User $user, $input)
    {
        return $this->isUserUpdatingHimself($user, $input);
    }

    /**
     * @param User $user
     * @param $input
     *
     * @return bool
     */
    private function canAdminUpdateHimself(User $user, $input)
    {
        return $user->role == User::ROLE_ADMIN && ($input['role'] != User::ROLE_ADMIN && $input['company_id'] != $user->company_id);
    }

    /**
     * @param User $user
     * @param $input
     *
     * @return bool
     */
    private function isProducerAdminUpdatingProducerUser(User $user, $input)
    {
        return $user->role == User::ROLE_PRODUCER_USER && $input['role'] == User::ROLE_USER && $input['company_id'] == null;
    }

    /**
     * @param User $user
     * @param $input
     *
     * @return bool
     */
    private function isCustomerAdminUpdatingCustomerUser(User $user, $input)
    {
        return $user->role == User::ROLE_CUSTOMER_USER && $input['role'] == User::ROLE_USER && $input['company_id'] == null;
    }

    /**
     * @param User $user
     * @param $input
     *
     * @return bool
     */
    private function isUserUpdatingHimself(User $user, $input)
    {
        return auth()->user()->id == $user->id && $input['role'] == $user->role && $input['company_id'] == $user->company_id;
    }
}
