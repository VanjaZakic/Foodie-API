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
     * @param Meal $id
     * @param MealService $mealService
     * @return array
     */
    public function show(Meal $id, MealService $mealService)
    {
        $meal = $mealService->show($id);

        return fractal()
            ->collection($meal)
            ->transformWith(new MealTransformer())
            ->toArray();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Meal $id
     * @return void
     */
    public function edit(Meal $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MealRequest $request
     * @param Meal $id
     * @param MealService $mealService
     * @return array
     */
    public function update(MealRequest $request, Meal $id, MealService $mealService)
    {
        $mealService->update($id, $request);

        return $this->show($id, $mealService);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Meal $id
     * @param MealService $mealService
     * @return array
     */
    public function destroy(Meal $id, MealService $mealService)
    {
        $mealService->destroy($id);

        return $this->index($mealService);
    }
}
