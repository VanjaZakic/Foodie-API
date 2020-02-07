<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\PaymentMethodStoreRequest;
use App\Services\PaymentMethodService;
use App\Transformers\PaymentMethodTransformer;
use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class PaymentMethodController
 * @package App\Http\Controllers\Api\V1
 */
class PaymentMethodController extends Controller
{
    /**
     * @var User $user
     */
    private $user;

    /**
     * @var PaymentMethodService
     */
    private PaymentMethodService $paymentMethodService;

    /**
     * PaymentMethodController constructor.
     *
     * @param PaymentMethodService $paymentMethodService
     * @param Request              $request
     */
    public function __construct(PaymentMethodService $paymentMethodService, Request $request)
    {
        $this->user                 = $request->user();
        $this->paymentMethodService = $paymentMethodService;
    }

    /**
     * @return array
     */
    public function index()
    {
        $paymentMethods = $this->paymentMethodService->getAll();

        return fractal()
            ->collection($paymentMethods)
            ->transformWith(new PaymentMethodTransformer())
            ->toArray();
    }

    /**
     * @param PaymentMethodStoreRequest $request
     *
     * @return array
     */
    public function store(PaymentMethodStoreRequest $request)
    {
        $paymentMethod = $this->paymentMethodService->store($request);

        return fractal()
            ->item($paymentMethod)
            ->transformWith(new PaymentMethodTransformer())
            ->toArray();
    }

    /**
     * @return array
     */
    public function show()
    {
        $paymentMethod = $this->paymentMethodService->get();

        return fractal()
            ->item($paymentMethod)
            ->transformWith(new PaymentMethodTransformer())
            ->toArray();
    }

    /**
     * @param string $paymentMethodId
     *
     * @return ResponseFactory|Response
     */
    public function destroy($paymentMethodId)
    {
        $this->user->findPaymentMethod($paymentMethodId)->delete();

        return response(null, 204);
    }
}
