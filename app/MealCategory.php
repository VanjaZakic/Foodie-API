<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MealCategory
 * @package App
 */
class MealCategory extends Model
{
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
}
