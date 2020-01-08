<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Company
 * @package App
 */
class Company extends Model
{
    use SoftDeletes;

    const TYPE_PRODUCER = 'producer';
    const TYPE_CUSTOMER = 'customer';

    /**
     * @var array
     */
    public static $types = [
        Company::TYPE_PRODUCER,
        Company::TYPE_CUSTOMER
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'address',
        'email',
        'image',
        'type',
        'lat',
        'lng'
    ];

    /**
     * @return HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return HasMany
     */
    public function mealCategory()
    {
        return $this->hasMany(MealCategory::class);
    }
}
