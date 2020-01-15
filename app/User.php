<?php

namespace App;

use App\Traits\UpdateUserTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes, UpdateUserTrait, Billable;

    const ROLE_ADMIN = 'admin';
    const ROLE_PRODUCER_ADMIN = 'producer_admin';
    const ROLE_PRODUCER_USER = 'producer_user';
    const ROLE_CUSTOMER_ADMIN = 'customer_admin';
    const ROLE_CUSTOMER_USER = 'customer_user';
    const ROLE_USER = 'user';

    /**
     * @var array
     */
    public static $roles = [
        User::ROLE_ADMIN,
        User::ROLE_PRODUCER_ADMIN,
        User::ROLE_PRODUCER_USER,
        User::ROLE_CUSTOMER_ADMIN,
        User::ROLE_CUSTOMER_USER,
        User::ROLE_USER
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'address',
        'email',
        'password',
        'role',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @param $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::Class);
    }

    /**
     * @return HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
