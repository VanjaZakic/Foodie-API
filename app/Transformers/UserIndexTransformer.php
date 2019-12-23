<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\User;

/**
 * Class UserTransformer
 * @package App\Transformers
 */
class UserIndexTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'links'      => [
                [
                    'rel' => 'self',
                    'uri' => '/users/' . $user->id,
                ]
            ],
        ];
    }
}
