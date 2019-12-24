<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Meal
 * @package App
 */
class Meal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'image',
        'price',
        'meal_category_id',
    ];

    /**
     * @return BelongsTo
     */
    public function mealCategory()
    {
        return $this->belongsTo(MealCategory::class);
    }
}
