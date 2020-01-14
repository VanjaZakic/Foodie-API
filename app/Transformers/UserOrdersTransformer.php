<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

/**
 * Class UserOrdersTransformer
 * @package App\Transformers
 */
class UserOrdersTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'         => $user->id,
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'role'       => $user->role,
            'orders'     => $user->orders()->with('meals')->getResults(),
        ];
    }
}
