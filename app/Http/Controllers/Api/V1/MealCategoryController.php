<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\Http\Requests\MealCategoryRequest;
use App\MealCategory;
use App\Services\MealCategoryService;
use App\Transformers\MealCategoryTransformer;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class MealCategoryController
 * @package App\Http\Controllers\Api\V1
 */
class MealCategoryController extends Controller
{
    /**
     * @var MealCategoryService
     */
    protected MealCategoryService $mealCategoryService;

    /**
     * MealCategoryController constructor.
     *
     * @param MealCategoryService $mealCategoryService
     */
    public function __construct(MealCategoryService $mealCategoryService)
    {
        $this->mealCategoryService = $mealCategoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Company $company
     * @return array
     * @throws RepositoryException
     */
    public function index(Company $company)
    {
        $mealCategories = $this->mealCategoryService->showAll($company);

        return fractal()
            ->collection($mealCategories)
            ->transformWith(new MealCategoryTransformer())
            ->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MealCategoryRequest $request
     * @return array
     * @throws ValidatorException
     */
    public function store(MealCategoryRequest $request)
    {
        $mealCategory = $this->mealCategoryService->store($request);

        return fractal()
            ->item($mealCategory)
            ->transformWith(new MealCategoryTransformer())
            ->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param MealCategory $mealCategory
     * @return array
     */
    public function show(MealCategory $mealCategory)
    {
        return fractal()
            ->item($mealCategory)
            ->transformWith(new MealCategoryTransformer())
            ->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MealCategoryRequest $request
     * @param MealCategory $mealCategory
     * @return array
     * @throws ValidatorException
     */
    public function update(MealCategoryRequest $request, MealCategory $mealCategory)
    {
        $mealCategory = $this->mealCategoryService->update($request, $mealCategory->id);

        return fractal()
            ->item($mealCategory)
            ->transformWith(new MealCategoryTransformer())
            ->toArray();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MealCategory $mealCategory
     * @return ResponseFactory|Response
     */
    public function destroy(MealCategory $mealCategory)
    {
        $this->mealCategoryService->destroy($mealCategory->id);

        return response(null, 204);
    }
}
