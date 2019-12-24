<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\MealCategory;

/**
 * Class MealCategoryTransformer
 * @package App\Transformers
 */
class MealCategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(MealCategory $mealCategory)
    {
        return [
            'id'    => (int)$mealCategory->id,
            'name'  => $mealCategory->name,
            'image' => $mealCategory->image,
        ];
    }
}
