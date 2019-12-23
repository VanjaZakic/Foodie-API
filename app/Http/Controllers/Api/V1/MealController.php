<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\MealRequest;
use App\Meal;
use App\Services\MealService;
use App\Transformers\MealTransformer;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class MealController
 * @package App\Http\Controllers\Api\V1
 */
class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param MealService $mealService
     * @return array
     */
    public function index(MealService $mealService)
    {
        $meals = $mealService->showAll();

        return fractal()
            ->collection($meals)
            ->transformWith(new MealTransformer())
            ->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MealRequest $request
     * @param MealService $mealService
     * @return array
     * @throws ValidatorException
     */
    public function store(MealRequest $request, MealService $mealService)
    {
        $meal = $mealService->store($request);

        return fractal()
            ->item($meal)
            ->transformWith(new MealTransformer())
            ->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param Meal $meal
     * @param MealService $mealService
     * @return array
     */
    public function show(Meal $meal, MealService $mealService)
    {
        $meal = $mealService->show($meal);

        return fractal()
            ->collection($meal)
            ->transformWith(new MealTransformer())
            ->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MealRequest $request
     * @param Meal $meal
     * @param MealService $mealService
     * @return array
     */
    public function update(MealRequest $request, Meal $meal, MealService $mealService)
    {
        $mealService->update($meal, $request);

        return $this->show($meal, $mealService);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Meal $meal
     * @param MealService $mealService
     * @return array
     */
    public function destroy(Meal $meal, MealService $mealService)
    {
        $mealService->destroy($meal);

        return $this->index($mealService);
    }
}
