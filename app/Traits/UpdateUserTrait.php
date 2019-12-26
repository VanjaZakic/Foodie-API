<?php

namespace App\Traits;

use App\User;

trait UpdateUserTrait
{
    public function canAdminUpdateUser($user, $input)
    {
        if ($this->canAdminUpdateHimself($user, $input)) {
            return false;
        }

        return true;
    }

    public function canProducerAdminUpdateUser($user, $input)
    {
        return ($this->isUserUpdatingHimself($user, $input) || $this->isProducerAdminUpdatingProducerUser($user, $input));
    }

    public function canCustomerAdminUpdateUser($user, $input)
    {
        return ($this->isUserUpdatingHimself($user, $input) || $this->isCustomerAdminUpdatingCustomerUser($user, $input));
    }

    public function canProducerUserUpdateUser($user, $input)
    {
        return $this->isUserUpdatingHimself($user, $input);
    }

    public function canCustomerUserUpdateUser($user, $input)
    {
        return $this->isUserUpdatingHimself($user, $input);
    }

    public function canUserUpdateUser($user, $input)
    {
        return $this->isUserUpdatingHimself($user, $input);
    }

    private function canAdminUpdateHimself($user, $input)
    {
        return $user->role == User::ROLE_ADMIN && ($input['role'] != User::ROLE_ADMIN || $input['company_id'] != $user->company_id);
    }

    private function isProducerAdminUpdatingProducerUser($user, $input)
    {
        return $user->role == User::ROLE_PRODUCER_USER && $input['role'] == User::ROLE_USER && $input['company_id'] == null;
    }

    private function isCustomerAdminUpdatingCustomerUser($user, $input)
    {
        return $user->role == User::ROLE_CUSTOMER_USER && $input['role'] == User::ROLE_USER && $input['company_id'] == null;
    }

    private function isUserUpdatingHimself($user, $input)
    {
        return auth()->user()->id == $user->id && $input['role'] == $user->role && $input['company_id'] == $user->company_id;
    }
}
