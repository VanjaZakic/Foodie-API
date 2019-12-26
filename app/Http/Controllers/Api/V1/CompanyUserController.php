<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\Http\Requests\StoreCompanyUserRequest;
use App\Services\CompanyUserService;
use App\Transformers\UserIndexTransformer;
use App\Transformers\UserTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class CompanyUserController
 * @package App\Http\Controllers\Api\V1
 */
class CompanyUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Company            $company
     * @param CompanyUserService $companyUserService
     *
     * @return array
     */
    public function index(Company $company, CompanyUserService $companyUserService)
    {
        $companyUsers           = $companyUserService->getPaginated($company->id, 5);
        $companyUsersCollection = $companyUsers->getCollection();

        return fractal()
            ->collection($companyUsersCollection)
            ->transformWith(new UserIndexTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($companyUsers))
            ->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Company                 $company
     *
     * @param StoreCompanyUserRequest $request
     *
     * @param CompanyUserService      $companyUserService
     *
     * @return mixed
     * @throws ValidatorException
     */
    public function store(Company $company, StoreCompanyUserRequest $request, CompanyUserService $companyUserService)
    {
        $user = $companyUserService->store($request, $company);

        if (!$user) {
            return response('Not Acceptable', 406);
        }

        return fractal()
            ->item($user)
            ->parseIncludes('company')
            ->transformWith(new UserTransformer())
            ->toArray();
    }
}
