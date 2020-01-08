<?php

namespace App\Services;

use App\Http\Requests\PaymentMethodStoreRequest;
use Illuminate\Support\Collection;

/**
 * Class PaymentMethodService
 * @package App\Services
 */
class PaymentMethodService
{
    /**
     * @var mixed
     */
    private $user;

    /**
     * PaymentMethodService constructor.
     */
    public function __construct()
    {
        $this->user = request()->user();
    }

    /**
     * @return Collection
     */
    public function getAll()
    {
        if (!$this->user->hasPaymentMethod()) {
            return new Collection();
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
