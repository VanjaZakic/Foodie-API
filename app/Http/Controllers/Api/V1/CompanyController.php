<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\CompanyRequest;
use App\Services\CompanyService;
use App\Transformers\CompanyTransformer;


/**
 * Class CompanyController
 * @package App\Http\Controllers\Api\V1
 */
class CompanyController extends Controller
{

    /**
     * @param CompanyRequest $request
     * @param CompanyService $companyService
     *
     * @return array
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CompanyRequest $request, CompanyService $companyService)
    {
        $company = $companyService->save($request);

        return fractal()
            ->item($company)
            ->transformWith(new CompanyTransformer())
            ->toArray();
    }
}
