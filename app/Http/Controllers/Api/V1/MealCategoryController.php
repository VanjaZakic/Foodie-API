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
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
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
     * @param MealCategory $id
     * @param MealCategoryService $mealCategoryService
     * @return array
     */
    public function show(MealCategory $id, MealCategoryService $mealCategoryService)
    {
        $mealCategory = $mealCategoryService->show($id);

        return fractal()
            ->collection($mealCategory)
            ->transformWith(new MealCategoryTransformer())
            ->toArray();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param MealCategory $id
     * @return void
     */
    public function edit(MealCategory $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MealCategoryRequest $request
     * @param MealCategory $id
     * @param MealCategoryService $mealCategoryService
     * @return array
     */
    public function update(MealCategoryRequest $request, MealCategory $id, MealCategoryService $mealCategoryService)
    {
        $mealCategoryService->update($id, $request);

        return $this->show($id, $mealCategoryService);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MealCategory $id
     * @param MealCategoryService $mealCategoryService
     * @return array
     */
    public function destroy(MealCategory $id, MealCategoryService $mealCategoryService)
    {
        $mealCategoryService->destroy($id);

        return $this->index($mealCategoryService);
    }
}
