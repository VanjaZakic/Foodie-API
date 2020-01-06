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
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class UserController
 * @package App\Http\Controllers\Api\V1
 */
class UserController extends Controller
{

    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        $users = $this->userService->getPaginated(5);
        $usersCollection = $users->getCollection();

        return fractal()
            ->collection($usersCollection)
            ->transformWith(new UserIndexTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($users))
            ->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     *
     * @return array
     * @throws ValidatorException
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->store($request);

        return fractal()
            ->item($user)
            ->parseIncludes('company')
            ->transformWith(new UserTransformer())
            ->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     *
     * @return array
     */
    public function show(User $user)
    {
        return fractal()
            ->item($user)
            ->parseIncludes('company')
            ->transformWith(new UserTransformer())
            ->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     *
     * @return array
     * @throws AuthorizationException
     * @throws ValidatorException
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', [$user, $request]);

        $user = $this->userService->update($request, $user->id);

        return fractal()
            ->item($user)
            ->parseIncludes('company')
            ->transformWith(new UserTransformer())
            ->toArray();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     *
     * @return Response
     */
    public function destroy(User $user)
    {
        $this->userService->softDelete($user->id);

        return response(null, 204);
    }
}
