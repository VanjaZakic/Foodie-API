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
     * @param MealCategory $mealCategory
     * @return array
     */
    public function transform(MealCategory $mealCategory)
    {
        return [
            'id'         => $mealCategory->id,
            'name'       => $mealCategory->name,
            'image'      => $mealCategory->image,
            'company_id' => $mealCategory->company_id,
        ];
    }
}
