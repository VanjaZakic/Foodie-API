<?php

namespace App\Services;

use App\Http\Requests\RegisterRequest;
use App\User;

/**
 * Class UserService
 * @package App\Services
 */
class UserService
{
    /**
     * @param RegisterRequest $request
     *
     * @return User
     */
    public function populateUser(RegisterRequest $request)
    {
        $user             = new User();
        $user->first_name = $request->get('first_name');
        $user->last_name  = $request->get('last_name');
        $user->phone      = $request->get('phone');
        $user->address    = $request->get('address');
        $user->email      = $request->get('email');
        $user->password   = bcrypt($request->get('password'));
        $user->role       = $request->get('role');
        return $user;
    }
}
