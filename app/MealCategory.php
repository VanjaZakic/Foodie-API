<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MealCategory
 * @package App
 *
 * @property int    $id
 * @property string $name
 * @property string $image
 * @property int    $company_id
 *
 * @property Company   company
 * @property Meal      meals
 */
class MealCategory extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'image',
        'company_id',
    ];

    /**
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return HasMany
     */
    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
