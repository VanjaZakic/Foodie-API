<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\RegisterRequest;
use App\Services\UserService;
use App\Transformers\UserTransformer;

/**
 * Class RegisterController
 * @package App\Http\Controllers
 */
class RegisterController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @param UserService     $userService
     *
     * @return array
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function register(RegisterRequest $request, UserService $userService)
    {
        $user = $userService->save($request);

        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer())
            ->toArray();
    }
}
