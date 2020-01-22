<?php

namespace App\Services;

use App\Company;
use App\Criteria\CompanyCriteria;
use App\Http\Requests\OrderRequest;
use App\Meal;
use App\Order;
use App\Repositories\OrderRepository;
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
        if (!$this->isValidMeals($request)) {
            return false;
        }

        $price = 0;
        foreach ($request->meals as $m) {
            $meal = Meal::find($m['meal_id']);
            $price += $meal->price * $m['quantity'];
        }

        if ($request->user()->company_id == $request->company_id) {
            $company = Company::find($request->company_id);
            $discount = $company->discount;
            $price = $price * $discount;
        }

        $order = $this->repository->create([
            'price'             => $price,
            'delivery_datetime' => $request->delivery_datetime,
            'user_id'           => $request->user()->id,
            'company_id'        => $request->company_id
        ]);

        foreach ($request->meals as $m) {
            $meal = Meal::find($m['meal_id']);
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
        if ($order->status == Order::STATUS_PROCESSING) {
            return $this->repository->update(['status' => Order::STATUS_DELIVERED], $order->id);
        }
        if ($order->status == Order::STATUS_ORDERED) {
            return $this->repository->update(['status' => Order::STATUS_PROCESSING], $order->id);
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
        return $this->repository->update(['status' => Order::STATUS_CANCELLED], $order->id);
    }

    /**
     * @param int $orderId
     * @return mixed
     */
    public function destroy($orderId)
    {
        return $this->repository->delete($orderId);
    }

    /**
     * @param $request
     * @return bool
     */
    private function isValidMeals($request)
    {
        $mealIds = array_reduce(
            array_map(
                function ($meal) {
                    return $meal['meal_id'];
                },
                $request->meals
            ),
            function ($a, $b) {
                return !is_array($a) ? [$b] : (in_array($b, $a) ? $a : [...$a, $b]);
            }
        );

        $count = Meal::join('meal_categories', 'meal_categories.id', '=', 'meals.meal_category_id')
            ->where('meal_categories.company_id', $request->company_id)
            ->whereIn('meals.id', $mealIds)
            ->count();

        return $count === count($mealIds);
    }
}
