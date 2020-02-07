<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Meal
 * @package App
 *
 * @property int    $id
 * @property string $name
 * @property string $description
 * @property string $image
 * @property int    $price
 * @property int    $meal_category_id
 *
 * @property MealCategory   mealCategory
 * @property Order          orders
 */
class Meal extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
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

    /**
     * @return BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class)
                    ->using(MealOrder::class)
                    ->withPivot([
                        'price',
                        'quantity',
                    ]);
    }
}
