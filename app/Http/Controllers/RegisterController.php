<?php

namespace App\Http\Controllers;

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
     *
     * @param UserService     $userService
     *
     * @return array
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
