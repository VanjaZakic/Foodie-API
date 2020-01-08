<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Services\CompanyService;
use App\Transformers\CompanyIndexTransformer;
use App\Transformers\CompanyTransformer;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Prettus\Repository\Exceptions\RepositoryException;
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
     * @throws RepositoryException
     */
    public function index()
    {
        $limit = config('fractal.pagination.default');

        $producerCompanies           = $this->companyService->getPaginated($limit);
        $producerCompaniesCollection = $producerCompanies->getCollection();

        return fractal()
            ->collection($producerCompaniesCollection)
            ->transformWith(new CompanyIndexTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($producerCompanies))
            ->toArray();
    }

    /**
     * @param CompanyStoreRequest $request
     *
     * @return array
     * @throws ValidatorException
     */
    public function store(CompanyStoreRequest $request)
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
     * @param CompanyUpdateRequest $request
     *
     * @param Company              $company
     *
     * @return array
     * @throws ValidatorException
     */
    public function update(CompanyUpdateRequest $request, Company $company)
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
