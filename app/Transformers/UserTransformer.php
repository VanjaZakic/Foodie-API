<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\User;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'      => (int) $user->id,
            'first_name'   => $user->first_name,
            'last_name'    => $user->year,
            'phone' => $user->phone,
            'address' => $user->address,
            'email' => $user->email,
            'role' => $user->role,
        ];
    }
}
