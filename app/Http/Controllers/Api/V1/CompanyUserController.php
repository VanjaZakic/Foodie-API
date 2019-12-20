<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Services\CompanyUserService;
use App\Transformers\UserIndexTransformer;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class CompanyUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(Company $company, CompanyUserService $companyUserService)
    {

        $users = $companyUserService->getAll($company->id);

        return fractal()
            ->collection($users)
            ->transformWith(new UserIndexTransformer())
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
    public function show(Company $company, User $user, CompanyUserService $userCompanyService)
    {
        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer())
            ->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Company            $company
     *
     * @param UserRequest        $request
     *
     * @param CompanyUserService $companyUserService
     *
     * @return Response
     * @throws ValidatorException
     */
    public function store(Company $company, UserRequest $request, CompanyUserService $companyUserService)
    {
        $user = $companyUserService->store($request, $company);

        if (!$user) {
            return response('Not Acceptable', 406);
        }

        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer())
            ->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest  $request
     * @param Company            $company
     * @param User               $user
     * @param CompanyUserService $companyUserService
     *
     * @return void
     * @throws ValidatorException
     */
    public function update(UpdateUserRequest $request, Company $company, User $user, companyUserService $companyUserService)
    {
        $user = $companyUserService->update($request, $user->id);

        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer())
            ->toArray();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company            $company
     * @param User               $user
     * @param CompanyUserService $companyUserService
     *
     * @return void
     */
    public function destroy(Company $company, User $user, CompanyUserService $companyUserService)
    {
        $companyUserService->delete($user->id);

        return response(null, 204);
    }
}
