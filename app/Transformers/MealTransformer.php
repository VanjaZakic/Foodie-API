<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Meal;

/**
 * Class MealTransformer
 * @package App\Transformers
 */
class MealTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Meal $meal
     * @return array
     */
    public function transform(Meal $meal)
    {
        return [
            'id'               => $meal->id,
            'name'             => $meal->name,
            'description'      => $meal->description,
            'image'            => $meal->image,
            'price'            => $meal->price,
            'meal_category_id' => $meal->meal_category_id,
        ];
    }
}
