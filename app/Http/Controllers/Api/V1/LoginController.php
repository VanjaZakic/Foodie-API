<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use App\Traits\ResponseTrait;
use App\User;
use Laravel\Passport\Http\Controllers\AccessTokenController;

/**
 * Class LoginController
 * @package App\Http\Controllers\Api\V1
 */
class LoginController extends AccessTokenController
{
    use ResponseTrait;

    /**
     * @param LoginRequest $request
     * @param UserService  $userService
     *
     * @return mixed
     */
    public function login(LoginRequest $request, UserService $userService)
    {
        $tokenRequest = $userService->login($request);
        if ($tokenRequest == null) {
            return $this->json('Wrong credentials', 422);
        }

        $tokenResponse = $this->issueToken($tokenRequest);
        $token         = $tokenResponse->getContent();
        $user          = User::whereEmail($request->get('email'))->first();

        $tokenInfo              = json_decode($token, true);
        $tokenInfo['user_type'] = $user->role;

        return $tokenInfo;
    }
}
