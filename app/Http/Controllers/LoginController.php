<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Traits\ResponseTrait;
use App\User;
use Illuminate\Http\Request;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Zend\Diactoros\ServerRequest;

class LoginController extends AccessTokenController
{
    use ResponseTrait;

    public function login(LoginRequest $request)
    {
        try {
            $tokenRequest = (new ServerRequest())->withParsedBody([
                'grant_type' => config('auth.passport.grant_type', 'password'),
                'client_id' => config('auth.passport.client_id'),
                'client_secret' => config('auth.passport.client_secret'),
                'username' => $request->get('username'),
                'password' => $request->get('password')
            ]);
            $tokenResponse = $this->issueToken($tokenRequest);
            $token         = $tokenResponse->getContent();
            $user = User::whereEmail($request->get('username'))->first();
        } catch(\Exception $exception) {
            return $this->json('Wrong credentials', 422);
        }

        $tokenInfo                      = json_decode($token, true);
        $tokenInfo['user_type']         = $user->role;

        return $tokenInfo;
    }
}
