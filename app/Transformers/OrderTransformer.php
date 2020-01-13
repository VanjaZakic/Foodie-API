<?php

namespace App\Transformers;

use App\Order;
use League\Fractal\TransformerAbstract;

/**
 * Class OrderTransformer
 * @package App\Transformers
 */
class OrderTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Order $order
     * @return array
     */
    public function transform(Order $order)
    {
        return [
            'id'                => $order->id,
            'price'             => $order->price,
            'discount'          => $order->discount,
            'delivery_datetime' => $order->delivery_datetime,
            'status'            => $order->status,
            'user_id'           => $order->user_id,
            'company_id'        => $order->company_id,
            'meals'             => $order->meals()->getResults(),
//            'meals'             => $order->meals()->allRelatedIds(),
        ];
    }
}
