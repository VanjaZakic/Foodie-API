<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Company
 * @package App
 * @property int          $id
 * @property string       $name
 * @property string       $phone
 * @property string       $address
 * @property string       $email
 * @property string       $image
 * @property string       $type
 * @property int          $lat
 * @property int          $lng
 * @property MealCategory $mealCategory
 * @property Order        $order
 */
class Company extends Model
{
    use SoftDeletes;

    const TYPE_PRODUCER = 'producer';
    const TYPE_CUSTOMER = 'customer';

    /**
     * @var array
     */
    public static array $types = [
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
        'discount',
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
    public function mealCategories()
    {
        return $this->hasMany(MealCategory::class);
    }

    /**
     * @return HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
