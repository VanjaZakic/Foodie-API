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
     * @param $user
     * @param $input
     * @return bool
     */
    public function canAdminUpdateUser($user, $input)
    {
        if ($this->canAdminUpdateHimself($user, $input)) {
            return false;
        }

        return true;
    }

    /**
     * @param $user
     * @param $input
     * @return bool
     */
    public function canProducerAdminUpdateUser($user, $input)
    {
        return ($this->isUserUpdatingHimself($user, $input) || $this->isProducerAdminUpdatingProducerUser($user, $input));
    }

    /**
     * @param $user
     * @param $input
     * @return bool
     */
    public function canCustomerAdminUpdateUser($user, $input)
    {
        return ($this->isUserUpdatingHimself($user, $input) || $this->isCustomerAdminUpdatingCustomerUser($user, $input));
    }

    /**
     * @param $user
     * @param $input
     * @return bool
     */
    public function canProducerUserUpdateUser($user, $input)
    {
        return $this->isUserUpdatingHimself($user, $input);
    }

    /**
     * @param $user
     * @param $input
     * @return bool
     */
    public function canCustomerUserUpdateUser($user, $input)
    {
        return $this->isUserUpdatingHimself($user, $input);
    }

    /**
     * @param $user
     * @param $input
     * @return bool
     */
    public function canUserUpdateUser($user, $input)
    {
        return $this->isUserUpdatingHimself($user, $input);
    }

    /**
     * @param $user
     * @param $input
     * @return bool
     */
    private function canAdminUpdateHimself($user, $input)
    {
        return $user->role == User::ROLE_ADMIN && ($input['role'] != User::ROLE_ADMIN || $input['company_id'] != $user->company_id);
    }

    /**
     * @param $user
     * @param $input
     * @return bool
     */
    private function isProducerAdminUpdatingProducerUser($user, $input)
    {
        return $user->role == User::ROLE_PRODUCER_USER && $input['role'] == User::ROLE_USER && $input['company_id'] == null;
    }

    /**
     * @param $user
     * @param $input
     * @return bool
     */
    private function isCustomerAdminUpdatingCustomerUser($user, $input)
    {
        return $user->role == User::ROLE_CUSTOMER_USER && $input['role'] == User::ROLE_USER && $input['company_id'] == null;
    }

    /**
     * @param $user
     * @param $input
     * @return bool
     */
    private function isUserUpdatingHimself($user, $input)
    {
        return auth()->user()->id == $user->id && $input['role'] == $user->role && $input['company_id'] == $user->company_id;
    }
}
