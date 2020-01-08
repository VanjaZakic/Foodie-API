<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\MealRequest;
use App\Meal;
use App\Services\MealService;
use App\Transformers\MealTransformer;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class MealController
 * @package App\Http\Controllers\Api\V1
 */
class MealController extends Controller
{
    /**
     * @var MealService
     */
    protected $mealService;

    /**
     * MealController constructor.
     *
     * @param MealService $mealService
     */
    public function __construct(MealService $mealService)
    {
        $this->mealService = $mealService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $mealCategoryId
     * @return array
     * @throws RepositoryException
     */
    public function index(int $mealCategoryId)
    {
        $meals = $this->mealService->showAll($mealCategoryId);

        return fractal()
            ->collection($meals)
            ->transformWith(new MealTransformer())
            ->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MealRequest $request
     * @return array
     * @throws ValidatorException
     */
    public function store(MealRequest $request)
    {
        $meal = $this->mealService->store($request);

        return fractal()
            ->item($meal)
            ->transformWith(new MealTransformer())
            ->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param Meal $meal
     * @return array
     */
    public function show(Meal $meal)
    {
        return fractal()
            ->item($meal)
            ->transformWith(new MealTransformer())
            ->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MealRequest $request
     * @param Meal $meal
     * @return array
     * @throws ValidatorException
     */
    public function update(MealRequest $request, Meal $meal)
    {
        $meal = $this->mealService->update($request, $meal->id);

        return fractal()
            ->item($meal)
            ->transformWith(new MealTransformer())
            ->toArray();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Meal $meal
     * @return ResponseFactory|Response
     */
    public function destroy(Meal $meal)
    {
        $this->mealService->destroy($meal->id);

        return response(null, 204);
    }
}
