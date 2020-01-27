<?php

namespace App\Http\Controllers\Api\V1;

use App\Order;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    private $paymentService;

    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * PaymentController constructor.
     *
     * @param PaymentService $paymentService
     * @param OrderService $orderService
     */
    public function __construct(PaymentService $paymentService, OrderService $orderService)
    {
        $this->paymentService = $paymentService;
        $this->orderService = $orderService;
    }

    /**
     * @param Request $request
     * @param Order $order
     * @return ResponseFactory|Response
     * @throws ValidatorException
     */
    public function store(Request $request, Order $order)
    {
        $paymentMethodId = $request->get('paymentMethodId');

        if ($order->paid) {
            return response('You already paid', 418);
        }

        $this->paymentService->charge($order->price * 100, $paymentMethodId);
        $this->orderService->paid($order);
    }
}
