<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Company
 * @package App
 */
class Company extends Model
{
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
