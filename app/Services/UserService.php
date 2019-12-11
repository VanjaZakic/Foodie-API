<?php

namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Traits\ResponseTrait;
use App\User;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Zend\Diactoros\ServerRequest;

/**
 * Class UserService
 * @package App\Services
 */
class UserService extends AccessTokenController
{
    use ResponseTrait;

    /**
     * @param RegisterRequest $request
     *
     * @return User
     */
    public function save(RegisterRequest $request)
    {
        $user = $this->populate($request);
        $user->save();

        return $user;
    }

    /**
     * @param RegisterRequest $request
     *
     * @return User
     */
    private function populate(RegisterRequest $request)
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

    /**
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function login(LoginRequest $request)
    {
        try {
            $tokenRequest  = (new ServerRequest())->withParsedBody([
                'grant_type'    => config('auth.passport.grant_type', 'password'),
                'client_id'     => config('auth.passport.client_id'),
                'client_secret' => config('auth.passport.client_secret'),
                'username'      => $request->get('email'),
                'password'      => $request->get('password'),
            ]);
            $tokenResponse = $this->issueToken($tokenRequest);
            $token         = $tokenResponse->getContent();
            $user          = User::whereEmail($request->get('email'))->first();
        } catch (\Exception $exception) {
            return $this->json('Wrong credentials', 422);
        }

        $tokenInfo              = json_decode($token, true);
        $tokenInfo['user_type'] = $user->role;

        return $tokenInfo;
    }

}
