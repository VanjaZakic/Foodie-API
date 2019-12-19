<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use App\Transformers\UserIndexTransformer;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
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
     * @return void
     */
    public function index(Company $company, UserService $userService)
    {
        $users = $userService->getAll($company->id);

        return fractal()
            ->collection($users)
            ->transformWith(new UserIndexTransformer())
            ->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Company     $company
     *
     * @param UserRequest $request
     *
     * @param UserService $userService
     *
     * @return Response
     * @throws ValidatorException
     */
    public function store(Company $company, UserRequest $request, UserService $userService)
    {
        $user = $userService->store($request, $company);

        if (!$user) {
            return response('Not Acceptable', 406);
        }

        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer())
            ->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param Company $company
     * @param User    $user
     *
     * @return array
     */
    public function show(Company $company, User $user, UserService $userService)
    {
        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer())
            ->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return void
     */
    public function update(UpdateUserRequest $request, Company $company, User $user, UserService $userService)
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
     * @param int $id
     *
     * @return void
     */
    public function destroy(Company $company, User $user, UserService $userService)
    {
        $userService->delete($user->id);

        return response(null, 204);
    }
}
