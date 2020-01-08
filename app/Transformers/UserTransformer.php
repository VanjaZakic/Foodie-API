<?php

namespace App\Transformers;

use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use App\User;

/**
 * Class UserTransformer
 * @package App\Transformers
 */
class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'company',
    ];

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
            'phone'      => $user->phone,
            'address'    => $user->address,
            'email'      => $user->email,
            'role'       => $user->role,
        ];
    }

    /**
     * Include Company
     *
     * @param User $user
     *
     * @return Item
     */
    public function includeCompany(User $user)
    {
        if ($user->company) {
            return $this->item($user->company, new CompanyIndexTransformer());
        }
    }
}
