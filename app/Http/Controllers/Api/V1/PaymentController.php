<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\PaymentRequest;
use App\Order;
use App\Services\OrderService;
use App\Services\PaymentService;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class PaymentController
 * @package App\Http\Controllers\Api\V1
 */
class PaymentController extends Controller
{
    /**
     * @var PaymentService
     */
    private PaymentService $paymentService;

    /**
     * @var OrderService
     */
    private OrderService $orderService;

    /**
     * PaymentController constructor.
     *
     * @param PaymentService $paymentService
     * @param OrderService   $orderService
     */
    public function __construct(PaymentService $paymentService, OrderService $orderService)
    {
        $this->paymentService = $paymentService;
        $this->orderService   = $orderService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PaymentRequest $request
     * @param Order          $order
     *
     * @throws ValidatorException
     */
    public function store(PaymentRequest $request, Order $order)
    {
        $this->paymentService->charge($order->price * 100, $request->paymentMethodId);

        $this->orderService->paid($order);
    }
}
