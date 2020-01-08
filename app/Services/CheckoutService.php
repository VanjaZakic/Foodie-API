<?php

namespace App\Services;

/**
 * Class CheckoutService
 * @package App\Services
 */
class CheckoutService
{
    /**
     * @var mixed
     */
    private $user;

    /**
     * CheckoutService constructor.
     */
    public function __construct()
    {
        $this->user = request()->user();
    }

    /**
     * @param $amount
     * @param $paymentMethodId
     */
    public function charge($amount, $paymentMethodId)
    {
        $this->user->charge($amount, $paymentMethodId);
    }
}
