<?php

namespace App\Services;

use App\User;
use Illuminate\Http\Request;

/**
 * Class CheckoutService
 * @package App\Services
 */
class CheckoutService
{
    /**
     * @var User user
     */
    private $user;

    /**
     * CheckoutService constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->user = $request->user();
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
