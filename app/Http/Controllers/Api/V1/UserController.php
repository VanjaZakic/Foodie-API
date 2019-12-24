<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\UserRequest;
use App\Services\UserService;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

/**
 * Class UserController
 * @package App\Http\Controllers\Api\V1
 */
class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     *
     * @param UserService $userService
     *
     * @return \Illuminate\Http\Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(UserRequest $request, UserService $userService)
    {
        $user = $userService->save($request);

        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer())
            ->toArray();
    }
}
