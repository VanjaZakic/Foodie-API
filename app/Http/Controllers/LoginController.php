<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use App\Traits\ResponseTrait;
use App\User;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Zend\Diactoros\ServerRequest;

/**
 * Class LoginController
 * @package App\Http\Controllers
 */
class LoginController extends AccessTokenController
{
    use ResponseTrait;

    /**
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function login(LoginRequest $request, UserService $userService)
    {
        $tokenInfo = $userService->login($request);
        return $tokenInfo;
    }
}
