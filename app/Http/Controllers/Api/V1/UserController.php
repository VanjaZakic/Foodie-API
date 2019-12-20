<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use App\Transformers\UserIndexTransformer;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class UserController
 * @package App\Http\Controllers\Api\V1
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param UserService $userService
     *
     * @return Response
     */
    public function index(UserService $userService)
    {
        $users = $userService->getAll();

        return fractal()
            ->collection($users)
            ->transformWith(new UserIndexTransformer())
            ->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     *
     * @param UserService      $userService
     *
     * @return Response
     */
    public function store(StoreUserRequest $request, UserService $userService)
    {
        $user = $userService->save($request);

        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer())
            ->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer())
            ->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param UserService       $userService
     * @param User              $user
     *
     * @return void
     * @throws ValidatorException
     */
    public function update(UpdateUserRequest $request, UserService $userService, User $user)
    {
        $user = $userService->update($request, $user->id);

        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer())
            ->toArray();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User        $user
     * @param UserService $userService
     *
     * @return Response
     * @throws ValidatorException
     */
    public function destroy(User $user, UserService $userService)
    {
        $userService->softDelete($user->id);

        return response(null, 204);
    }
}
