<?php

namespace App\Transformers;

use Laravel\Cashier\PaymentMethod;
use League\Fractal\TransformerAbstract;

/**
 * Class PaymentMethodTransformer
 * @package App\Transformers
 */
class PaymentMethodTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param PaymentMethod $paymentMethod
     * @return array
     */
    public function transform($paymentMethod)
    {
        if (is_null($paymentMethod)) {
            return [];
        }

        return [
            'id' => $paymentMethod->id,
            'card_brand' => $paymentMethod->card->brand,
            'card_last_four' => $paymentMethod->card->last4
        ];
    }
}
