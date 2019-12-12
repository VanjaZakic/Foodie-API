<?php

namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Zend\Diactoros\ServerRequest;
use App\Repositories\UserRepository;

/**
 * Class UserService
 * @package App\Services
 */
class UserService
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * UserService constructor.
     *
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param RegisterRequest $request
     *
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function save(RegisterRequest $request)
    {
        return $user = $this->repository->create($request->all());
    }

    /**
     * @param LoginRequest $request
     *
     * @return ServerRequest|null
     */
    public function login(LoginRequest $request)
    {
        try {
            $tokenRequest = (new ServerRequest())->withParsedBody([
                'grant_type'    => config('auth.passport.grant_type', 'password'),
                'client_id'     => config('auth.passport.client_id'),
                'client_secret' => config('auth.passport.client_secret'),
                'username'      => $request->get('email'),
                'password'      => $request->get('password'),
            ]);

            return $tokenRequest;
        } catch (\Exception $exception) {
            return null;
        }
    }
}
