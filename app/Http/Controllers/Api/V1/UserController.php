<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Pipes\UpdateAuthorization;
use App\Pipes\UpdateRequestBusinessValidation;
use App\Pipes\UpdateRequestValidation;
use App\Services\UserService;
use App\Transformers\UserIndexTransformer;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Pipeline;
use Illuminate\Http\Request;
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
     * @param Request $request
     *
     * @return array
     */
    public function index(Request $request)
    {
        $users           = $this->userService->getPaginated($request->limit);
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
     * @param UserStoreRequest $request
     *
     * @return array
     * @throws ValidatorException
     */
    public function store(UserStoreRequest $request)
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
     * @param UserUpdateRequest $request
     * @param User              $user
     *
     * @return array
     * @throws ValidatorException
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $pipeline = app(Pipeline::class)
            ->send($request)
            ->through([
                UpdateRequestValidation::class,
                UpdateAuthorization::class,
                UpdateRequestBusinessValidation::class
            ])
            ->thenReturn($request);

        if ($pipeline instanceof JsonResponse) {
            return $pipeline;
        }

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
