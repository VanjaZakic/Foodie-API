<?php

namespace App\Policies;

use App\Company;
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
     * Determine whether the user can show all orders from the company.
     *
     * @param User $user
     * @param Company $company
     * @return mixed
     */
    public function showAll(User $user, Company $company)
    {
        return $company->id === $user->company_id;
    }

    /**
     * Determine whether the user can show the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function show(User $user, Order $order)
    {
        return $order->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function update(User $user, Order $order)
    {
        return $order->user_id === $user->id;
    }

    /**
     * Determine whether the user can cancel the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function cancel(User $user, Order $order)
    {
//        if ($order->status == 'ordered') {
//            return $order->user_id === $user->id;
//        }
// producer treba da moze da cancel ako je ordered iako nije isti id

        if ($order->status == 'ordered') {
            if ($order->user_id === $user->id) {
                return true;
            }
        }
        if ($user->role == 'producer_admin' || $user->role == 'producer_user') {
            return $order->company_id === $user->company_id;
        }
    }

    /**
     * Determine whether the user can delete the order.
     *
     * @param User $user
     * @param Order $order
     * @return mixed
     */
    public function delete(User $user, Order $order)
    {
        return $order->company_id === $user->company_id;
    }
}
