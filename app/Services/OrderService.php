<?php

namespace App\Services;

use App\Company;
use App\Criteria\CompanyCriteria;
use App\Http\Requests\OrderRequest;
use App\Order;
use App\Repositories\MealRepository;
use App\Repositories\OrderRepository;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\DB;
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
    protected $orderRepository;

    /**
     * @var MealRepository
     */
    protected $mealRepository;

    /**
     * OrderService constructor.
     *
     * @param OrderRepository $orderRepository
     * @param MealRepository $mealRepository
     */
    public function __construct(OrderRepository $orderRepository, MealRepository $mealRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->mealRepository  = $mealRepository;
    }

    /**
     * @param Company $company
     * @return mixed
     * @throws RepositoryException
     */
    public function producerShowAll($company)
    {
        $this->orderRepository->pushCriteria(new CompanyCriteria($company));
        return $this->orderRepository->all();
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
     * @throws \Exception
     */
    public function store($request)
    {
        DB::beginTransaction();

        try {
            $mealIds = [];
            foreach ($request->meals as $meal) {
                $mealIds[] = $meal['meal_id'];
            }

            $count = $this->mealRepository->countIds($request->company_id, $mealIds);

            if ($count !== count($mealIds)) {
                return false;
            }

            $meals = $this->mealRepository->getMeals($mealIds);

            $price = 0;
            foreach ($meals as $meal) {
                foreach ($request->meals as $m) {
                    if ($m['meal_id'] == $meal->id) {
                        $meal['quantity'] = $m['quantity'];
                    }
                }
                $price += $meal->price * $meal->quantity;
            }

            if ($request->user()->company_id == $request->company_id) {
                $company = Company::find($request->company_id);
                $discount = $company->discount;
                $price = $price * $discount;
            }

            $order = $this->orderRepository->create([
                'price'             => $price,
                'delivery_datetime' => $request->delivery_datetime,
                'user_id'           => $request->user()->id,
                'company_id'        => $request->company_id
            ]);
        } catch(ValidationException $e)
        {
            DB::rollback();
            return response(null, 400);
        } catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }

        try {
            foreach ($meals as $meal) {
                $order->meals()->attach($meal->id, ['price' => $meal->price, 'quantity' => $meal->quantity]);
            }
        } catch(ValidationException $e)
        {
            DB::rollback();
            return response(null, 400);
        } catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }

        DB::commit();
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
            return $this->orderRepository->update(['status' => Order::STATUS_DELIVERED], $order->id);
        }
        if ($order->status == Order::STATUS_ORDERED) {
            return $this->orderRepository->update(['status' => Order::STATUS_PROCESSING], $order->id);
        }
        return false;
    }

    /**
     * @param Order $order
     * @return mixed
     * @throws ValidatorException
     */
    public function paid($order)
    {
         return $this->orderRepository->update(['paid' => 1], $order->id);
    }

    /**
     * @param Order $order
     * @return mixed
     * @throws ValidatorException
     */
    public function cancel($order)
    {
        return $this->orderRepository->update(['status' => Order::STATUS_CANCELLED], $order->id);
    }

    /**
     * @param int $orderId
     * @return mixed
     */
    public function destroy($orderId)
    {
        return $this->orderRepository->delete($orderId);
    }
}
