<?php

namespace App\Services;

use App\Company;
use App\Criteria\CompanyCriteria;
use App\Http\Requests\OrderRequest;
use App\Meal;
use App\Order;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class OrderService
 * @package App\Services
 */
class OrderService
{
    /**
     * @var OrderRepository
     */
    protected $repository;

    /**
     * OrderService constructor.
     *
     * @param OrderRepository $repository
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Company $company
     * @return mixed
     * @throws RepositoryException
     */
    public function producerShowAll($company)
    {
        $this->repository->pushCriteria(new CompanyCriteria($company));
        return $this->repository->all();
    }

    /**
     * @param Company $company
     * @return mixed
     */
    public function customerShowAll($company)
    {
        return $company->users()->with('orders')->getResults();
    }

    /**
     * @param Company $company
     * @return mixed
     */
    public function totalPrice($company)
    {
        $price = 0;
        foreach ($company->users as $user) {
            $orders = $user->orders;
            foreach ($orders as $order) {
                $price += $order['price'];
            }
        }
        return $price;
    }

    /**
     * @param OrderRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function store($request)
    {
        foreach ($request->meals as $m) {
            $meal = new Meal();
            $meal = $meal->find($m['meal_id']);
            if ($meal->mealCategory->company_id != $request->company_id) {
                return false;
            }
        }

        $price = 0;
        foreach ($request->meals as $m) {
            $meal = new Meal();
            $meal = $meal->find($m['meal_id']);
            $price += $meal->price * $m['quantity'];
        }

        $order = $this->repository->create([
            'price'             => $price,
            'delivery_datetime' => $request->delivery_datetime,
            'user_id'           => Auth::user()->id,
            'company_id'        => $request->company_id
        ]);

        foreach ($request->meals as $m) {
            $meal = new Meal();
            $meal = $meal->find($m['meal_id']);
            $order->meals()->attach($meal->id, ['price' => $meal->price, 'quantity' => $m['quantity']]);
        }

        return $order;
    }

    /**
     * @param Order $order
     * @param OrderRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function update($request, $order)
    {
        foreach ($request->meals as $m) {
            $meal = new Meal();
            $meal = $meal->find($m['meal_id']);
            if ($meal->mealCategory->company_id != $order->company_id) {
                return false;
            }
        }

        $price = 0;
        foreach ($request->meals as $m) {
            $meal = new Meal();
            $meal = $meal->find($m['meal_id']);
            $price += $meal->price * $m['quantity'];
        }

        $order = $this->repository->update(
            [
                'price'             => $price,
                'delivery_datetime' => $request->delivery_datetime
            ],
            $order->id
        );

        $order->meals()->detach();
        foreach ($request->meals as $m) {
            $meal = new Meal();
            $meal = $meal->find($m['meal_id']);
            $order->meals()->attach($meal->id, ['price' => $meal->price, 'quantity' => $m['quantity']]);
        }

        return $order;
    }

    /**
     * @param Order $order
     * @return mixed
     * @throws ValidatorException
     */
    public function producerUpdateStatus($order)
    {
        if ($order->status == 'processing') {
            return $this->repository->update(['status' => 'delivered'], $order->id);
        }
        if ($order->status == 'ordered') {
            return $this->repository->update(['status' => 'processing'], $order->id);
        }
        return false;
    }

    /**
     * @param Order $order
     * @return mixed
     * @throws ValidatorException
     */
    public function cancel($order)
    {
        return $this->repository->update(['status' => 'cancelled'], $order->id);
    }

    /**
     * @param int $orderId
     * @return mixed
     */
    public function destroy($orderId)
    {
        return $this->repository->delete($orderId);
    }
}
