<?php

namespace App\Services;

use App\User;
use Illuminate\Http\Request;

/**
 * Class PaymentService
 * @package App\Services
 */
class PaymentService
{
    /**
     * @var User user
     */
    private $user;

    /**
     * PaymentService constructor.
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
