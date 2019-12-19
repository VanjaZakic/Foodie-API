<?php

namespace App\Services;

use App\Criteria\CompanyUsersCriteria;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
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

    /**
     * @param $companyId
     *
     * @return mixed
     */
    public function getAll($companyId)
    {
        $users = $this->repository->getByCriteria(new CompanyUsersCriteria($companyId));
        return $users;
    }

    /**
     * @param RegisterRequest $request
     *
     * @param                 $company
     *
     * @return mixed
     * @throws ValidatorException
     */
    public function store($request, $company)
    {
        $user = $this->repository->findByField('company_id', $company->id);

        if (!count($user)) {
            $request = array_merge($request->all(), ['company_id' => $company->id]);
            return $this->repository->create($request);
        }

        return null;
    }

    /**
     * @param $request
     * @param $id
     *
     * @return mixed
     * @throws ValidatorException
     */
    public function update($request, $id)
    {
        return $this->repository->update($request->all(), $id);
    }

    /**
     * @param $id
     *
     * @return int
     */
    public function delete($id)
    {
        return $this->repository->delete($id);
    }


}
