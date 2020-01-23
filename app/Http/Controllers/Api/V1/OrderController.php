<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\Http\Requests\OrderRequest;
use App\Order;
use App\Services\OrderService;
use App\Transformers\OrderTransformer;
use App\Transformers\UserOrdersTransformer;
use Illuminate\Contracts\Routing\ResponseFactory;
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
            'meta' => ['Total price' => $totalPrice]
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OrderRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function store(OrderRequest $request)
    {
        $order = $this->orderService->store($request);

        if (!$order) {
            return response()->json([
                'error' => 'Meals must be from the same company'], 400);
        }
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
     * Update status to specified resource in storage.
     *
     * @param Order $order
     * @return mixed
     * @throws ValidatorException
     */
    public function producerUpdateStatus(Order $order)
    {
        $status = $this->orderService->producerUpdateStatus($order);

        if (!$status) {
            return response()->json([
                'error' => 'Status can not be changed'], 400);
        }
        return response(null, 204);
    }

    /**
     * Cancel the specified resource in storage.
     *
     * @param Order $order
     * @return ResponseFactory|Response
     * @throws ValidatorException
     */
    public function cancel(Order $order)
    {
        $this->orderService->cancel($order);

        return response(null, 204);

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
