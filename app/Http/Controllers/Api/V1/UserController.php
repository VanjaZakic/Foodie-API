<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use App\Transformers\UserTransformer;
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
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        //
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
     * @param int $id
     *
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return void
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return void
     */
    public function destroy($id)
    {
        //
    }
}
