<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Order
 * @package App
 */
class Order extends Model
{
    use SoftDeletes;

    const STATUS_ORDERED = 'ordered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_PROCESSING = 'processing';
    const STATUS_DELIVERED = 'delivered';

    /**
     * @var array
     */
    public static $statuses = [
        Order::STATUS_ORDERED,
        Order::STATUS_CANCELLED,
        Order::STATUS_PROCESSING,
        Order::STATUS_DELIVERED
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'price',
        'delivery_datetime',
        'user_id',
        'company_id',
        'status',
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return BelongsToMany
     */
    public function meals()
    {
        return $this->belongsToMany(Meal::class)
                    ->using(MealOrder::class)
                    ->withPivot([
                        'price',
                        'quantity',
                    ]);
    }
}
