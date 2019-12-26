<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\Http\Requests\MealCategoryRequest;
use App\MealCategory;
use App\Services\MealCategoryService;
use App\Transformers\MealCategoryTransformer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class MealCategoryController
 * @package App\Http\Controllers\Api\V1
 */
class MealCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param MealCategoryService $mealCategoryService
     * @param Company $company
     * @return array
     */
    public function index(MealCategoryService $mealCategoryService, Company $company)
    {
        $mealCategories = $mealCategoryService->showAll($company);

        return fractal()
            ->collection($mealCategories)
            ->transformWith(new MealCategoryTransformer())
            ->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MealCategoryRequest $request
     * @param MealCategoryService $mealCategoryService
     * @param Company $company
     * @return array
     * @throws ValidatorException
     * @throws AuthorizationException
     */
    public function store(MealCategoryRequest $request, MealCategoryService $mealCategoryService, Company $company)
    {
        $this->authorize('create', $company);

        $mealCategory = $mealCategoryService->store($request, $company);

        return fractal()
            ->item($mealCategory)
            ->transformWith(new MealCategoryTransformer())
            ->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param MealCategory $mealCategory
     * @param MealCategoryService $mealCategoryService
     * @return array
     */
    public function show(MealCategory $mealCategory, MealCategoryService $mealCategoryService)
    {
        $mealCategory = $mealCategoryService->show($mealCategory);

        return fractal()
            ->collection($mealCategory)
            ->transformWith(new MealCategoryTransformer())
            ->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MealCategoryRequest $request
     * @param MealCategory $mealCategory
     * @param MealCategoryService $mealCategoryService
     * @return array
     * @throws AuthorizationException
     */
    public function update(MealCategoryRequest $request, MealCategory $mealCategory, MealCategoryService $mealCategoryService)
    {
        $this->authorize('update', $mealCategory);

        $mealCategoryService->update($mealCategory, $request);

        return $this->show($mealCategory, $mealCategoryService);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MealCategory $mealCategory
     * @param MealCategoryService $mealCategoryService
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(MealCategory $mealCategory, MealCategoryService $mealCategoryService)
    {
        $this->authorize('delete', $mealCategory);

        $mealCategoryService->destroy($mealCategory);

        return response()->json([
            'Item is deleted.'
        ]);
    }
}
