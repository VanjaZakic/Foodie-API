<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Services\CompanyService;
use App\Transformers\CompanyIndexTransformer;
use App\Transformers\CompanyTransformer;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class CompanyController
 * @package App\Http\Controllers\Api\V1
 */
class CompanyController extends Controller
{

    /**
     * @param CompanyService $companyService
     *
     * @return array
     */
    public function index(CompanyService $companyService)
    {
        $producerCompanies           = $companyService->getPaginated(5);
        $producerCompaniesCollection = $producerCompanies->getCollection();

        return fractal()
            ->collection($producerCompaniesCollection)
            ->transformWith(new CompanyIndexTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($producerCompanies))
            ->toArray();
    }

    /**
     * @param StoreCompanyRequest $request
     * @param CompanyService      $companyService
     *
     * @return array
     * @throws ValidatorException
     */
    public function store(StoreCompanyRequest $request, CompanyService $companyService)
    {
        $company = $companyService->store($request);

        return fractal()
            ->item($company)
            ->transformWith(new CompanyTransformer())
            ->toArray();
    }

    /**
     * @param Company $company
     *
     * @return array
     */
    Public function show(Company $company)
    {
        return fractal()
            ->item($company)
            ->transformWith(new CompanyTransformer())
            ->toArray();
    }

    /**
     * @param UpdateCompanyRequest $request
     *
     * @param CompanyService       $companyService
     * @param Company              $company
     *
     * @return array
     * @throws ValidatorException
     */
    public function update(UpdateCompanyRequest $request, CompanyService $companyService, Company $company)
    {
        $company = $companyService->update($request, $company->id);

        return fractal()
            ->item($company)
            ->transformWith(new CompanyTransformer())
            ->toArray();
    }

    /**
     * @param CompanyService $companyService
     * @param Company        $company
     *
     * @return ResponseFactory|Response
     */
    public function destroy(CompanyService $companyService, Company $company)
    {
        $companyService->softDelete($company->id);

        return response(null, 204);
    }
}
