<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\Http\Requests\OrderRequest;
use App\Order;
use App\Services\OrderService;
use App\Transformers\OrderTransformer;
use App\Transformers\UserOrdersTransformer;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class OrderController
 * @package App\Http\Controllers\Api\V1
 */
class OrderController extends Controller
{
    /**
     * @var OrderService
     */
    protected $orderService;

    /**
     * OrderController constructor.
     *
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Company $company
     * @return array
     * @throws RepositoryException
     */
    public function producerIndex(Company $company)
    {
        $orders = $this->orderService->producerShowAll($company);

        return fractal()
            ->collection($orders)
            ->transformWith(new OrderTransformer())
            ->toArray();
    }

    /**
     * Display a listing of the resource with price.
     *
     * @param Company $company
     * @return array
     */
    public function customerIndex(Company $company)
    {
        $userOrders = $this->orderService->customerShowAll($company);
        $totalPrice = $this->orderService->totalPrice($company);

        return [
            fractal()
                ->collection($userOrders)
                ->transformWith(new UserOrdersTransformer())
                ->toArray(),
            $totalPrice
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OrderRequest $request
     * @return array
     * @throws ValidatorException
     */
    public function store(OrderRequest $request)
    {
        $order = $this->orderService->store($request);

        return fractal()
            ->item($order)
            ->transformWith(new OrderTransformer())
            ->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param Order $order
     * @return array
     */
    public function show(Order $order)
    {
        return fractal()
            ->item($order)
            ->transformWith(new OrderTransformer())
            ->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OrderRequest $request
     * @param Order $order
     * @return array
     * @throws ValidatorException
     */
    public function update(OrderRequest $request, Order $order)
    {
        $order = $this->orderService->update($request, $order->id);

        return fractal()
            ->item($order)
            ->transformWith(new OrderTransformer())
            ->toArray();
    }

    /**
     * Cancel the specified resource in storage.
     *
     * @param Order $order
     * @return JsonResponse
     * @throws ValidatorException
     */
    public function cancel(Order $order)
    {
        $this->orderService->cancel($order);

        return response()->json([
            'Item is cancelled.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Order $order
     * @return ResponseFactory|Response
     */
    public function destroy(Order $order)
    {
        $this->orderService->destroy($order->id);

        return response(null, 204);
    }
}
