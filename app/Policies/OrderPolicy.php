<?php

namespace App\Policies;

use App\Order;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class OrderPolicy
 * @package App\Policies
 */
class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can show the order.
     *
     * @param User  $user
     * @param Order $order
     * @return bool
     */
    public function view(User $user, Order $order)
    {
        return $order->user_id === $user->id;
    }

    /**
     * Determine whether the user can update status or delete the order.
     *
     * @param User  $user
     * @param Order $order
     * @return bool
     */
    public function ifCompanyId(User $user, Order $order)
    {
        return $order->company_id === $user->company_id;
    }

    /**
     * Determine whether the user can cancel the order.
     *
     * @param User  $user
     * @param Order $order
     * @return bool
     */
    public function cancel(User $user, Order $order)
    {
        return $order->status == Order::STATUS_ORDERED ?
                    ($order->user_id === $user->id ?
                        true : ($user->role == User::ROLE_PRODUCER_ADMIN || $user->role == User::ROLE_PRODUCER_USER ?
                                    $order->company_id === $user->company_id : false
                               )
                    ) : false;
    }
}
