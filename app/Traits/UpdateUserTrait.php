<?php

namespace App\Traits;

use App\User;
use Illuminate\Support\Facades\Auth;

trait UpdateUserTrait
{
    public function canAdminUpdateUser($request)
    {
        if ($this->canAdminUpdateHimself($request)) {
            return false;
        }

        return true;
    }

    public function canProducerAdminUpdateUser($request)
    {
        return ($this->isUserUpdatingHimself($request) || $this->isProducerAdminUpdatingProducerUser($request));
    }

    public function canCustomerAdminUpdateUser($request)
    {
        return ($this->isUserUpdatingHimself($request) || $this->isCustomerAdminUpdatingCustomerUser($request));
    }

    public function canProducerUserUpdateUser($request)
    {
        return $this->isUserUpdatingHimself($request);
    }

    public function canCustomerUserUpdateUser($request)
    {
        return $this->isUserUpdatingHimself($request);
    }

    public function canUserUpdateUser($request)
    {
        return $this->isUserUpdatingHimself($request);
    }

    private function canAdminUpdateHimself($request)
    {
        return $request->user->role == User::ROLE_ADMIN && ($request->role != User::ROLE_ADMIN || $request->company_id != $request->user->company_id);
    }

    private function isProducerAdminUpdatingProducerUser($request)
    {
        return $request->user->role == User::ROLE_PRODUCER_USER && $request->role == User::ROLE_USER && $request->company_id == null;
    }

    private function isCustomerAdminUpdatingCustomerUser($request)
    {
        return $request->user->role == User::ROLE_CUSTOMER_USER && $request->role == User::ROLE_USER && $request->company_id == null;
    }

    private function isUserUpdatingHimself($request)
    {
        return Auth::user()->id == $request->user->id && $request->role == $request->user->role && $request->company_id == $request->user->company_id;
    }


}
