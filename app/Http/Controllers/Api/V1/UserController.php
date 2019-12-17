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
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
