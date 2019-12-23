<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\MealCategoryRequest;
use App\MealCategory;
use App\Services\MealCategoryService;
use App\Transformers\MealCategoryTransformer;
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
     * @return array
     */
    public function index(MealCategoryService $mealCategoryService)
    {
        $mealCategories = $mealCategoryService->showAll();

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
     * @return array
     * @throws ValidatorException
     */
    public function store(MealCategoryRequest $request, MealCategoryService $mealCategoryService)
    {
        $mealCategory = $mealCategoryService->store($request);

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
     */
    public function update(MealCategoryRequest $request, MealCategory $mealCategory, MealCategoryService $mealCategoryService)
    {
        $mealCategoryService->update($mealCategory, $request);

        return $this->show($mealCategory, $mealCategoryService);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MealCategory $mealCategory
     * @param MealCategoryService $mealCategoryService
     * @return array
     */
    public function destroy(MealCategory $mealCategory, MealCategoryService $mealCategoryService)
    {
        $mealCategoryService->destroy($mealCategory);

        return $this->index($mealCategoryService);
    }
}
