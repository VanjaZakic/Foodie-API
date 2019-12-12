<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\User;

/**
 * Class UserTransformer
 * @package App\Transformers
 */
class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'         => (int)$user->id,
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'phone'      => $user->phone,
            'address'    => $user->address,
            'email'      => $user->email,
            'role'       => $user->role,
        ];
    }
}
