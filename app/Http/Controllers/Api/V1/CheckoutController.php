<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\CheckoutService;
use Illuminate\Http\Request;

/**
 * Class CheckoutController
 * @package App\Http\Controllers\Api\V1
 */
class CheckoutController extends Controller
{
    /**
     * @var CheckoutService
     */
    private $checkoutService;

    /**
     * CheckoutController constructor.
     *
     * @param CheckoutService $checkoutService
     */
    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $amount          = $request->get('amount');
        $paymentMethodId = $request->get('paymentMethodId');
        $this->checkoutService->charge($amount, $paymentMethodId);
    }
}
