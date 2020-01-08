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
     * @var CompanyService
     */
    private $companyService;

    /**
     * CompanyController constructor.
     *
     * @param CompanyService $companyService
     */
    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * @return array
     */
    public function index()
    {
        $producerCompanies = $this->companyService->getPaginated(5);
        $producerCompaniesCollection = $producerCompanies->getCollection();

        return fractal()
            ->collection($producerCompaniesCollection)
            ->transformWith(new CompanyIndexTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($producerCompanies))
            ->toArray();
    }

    /**
     * @param StoreCompanyRequest $request
     *
     * @return array
     * @throws ValidatorException
     */
    public function store(StoreCompanyRequest $request)
    {
        $company = $this->companyService->store($request);

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
     * @param Company $company
     *
     * @return array
     * @throws ValidatorException
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $company = $this->companyService->update($request, $company->id);

        return fractal()
            ->item($company)
            ->transformWith(new CompanyTransformer())
            ->toArray();
    }

    /**
     * @param Company $company
     *
     * @return ResponseFactory|Response
     */
    public function destroy(Company $company)
    {
        $this->companyService->softDelete($company->id);

        return response(null, 204);
    }
}
