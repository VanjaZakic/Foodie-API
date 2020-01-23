<?php

namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Exception;
use Illuminate\Support\Facades\DB;
use Prettus\Validator\Exceptions\ValidatorException;
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
     * @param LoginRequest $request
     *
     * @return ServerRequest|null
     */
    public function login(LoginRequest $request)
    {
        try {
            return (new ServerRequest())->withParsedBody([
                'grant_type'    => config('auth.passport.grant_type', 'password'),
                'client_id'     => config('auth.passport.client_id'),
                'client_secret' => config('auth.passport.client_secret'),
                'username'      => $request->get('email'),
                'password'      => $request->get('password'),
            ]);
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * @param $limit
     *
     * @return mixed
     */
    public function getPaginated($limit = null)
    {
        return $this->repository->paginate($limit);
    }

    /**
     * @param UserStoreRequest $request
     *
     * @return mixed
     * @throws ValidatorException
     */
    public function store(UserStoreRequest $request)
    {
        return $this->repository->create($request->all());
    }

    /**
     * @param UserUpdateRequest $request
     * @param                   $id
     *
     * @return mixed
     * @throws ValidatorException
     */
    public function update(UserUpdateRequest $request, $id)
    {
        return $this->repository->update($request->all(), $id);
    }

    /**
     * @param $id
     *
     * @return int
     */
    public function softDelete($id)
    {
        return $this->repository->delete($id);
    }


}
