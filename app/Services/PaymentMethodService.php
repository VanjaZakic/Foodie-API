<?php

namespace App\Services;

use App\Http\Requests\PaymentMethodStoreRequest;
use App\User;
use Illuminate\Http\Request;

/**
 * Class PaymentMethodService
 * @package App\Services
 */
class PaymentMethodService
{
    /**
     * @var User $user
     */
    private $user;

    /**
     * PaymentMethodService constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->user = $request->user();
    }

    /**
     * @return array
     */
    public function getAll()
    {
        if (!$this->user->hasPaymentMethod()) {
            return [];
        }

        return $this->user->paymentMethods();
    }

    /**
     * @return mixed
     */
    public function get()
    {
        if (!$this->user->hasPaymentMethod()) {
            return null;
        }

        return $this->user->defaultPaymentMethod();
    }

    /**
     * @param PaymentMethodStoreRequest $request
     *
     * @return mixed
     */
    public function store(PaymentMethodStoreRequest $request)
    {
        if (!$this->user->stripe_id) {
            $this->user->createAsStripeCustomer();
        }

        $stripeToken   = $request->get('stripeToken');
        $paymentMethod = $this->user->updateDefaultPaymentMethod($stripeToken);

        $paymentMethod = $this->user->findPaymentMethod($paymentMethod->id);
        return $paymentMethod;
    }
}
