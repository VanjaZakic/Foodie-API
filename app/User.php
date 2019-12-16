<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

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
    
    public static $roles = [
        'ADMIN'          => 'admin',
        'PRODUCER_ADMIN' => 'producer_admin',
        'PRODUCER_USER'  => 'producer_user',
        'CUSTOMER_ADMIN' => 'customer_admin',
        'CUSTOMER_USER'  => 'customer_user',
        'USER'           => 'user'
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

}
