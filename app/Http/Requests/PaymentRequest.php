<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class PaymentRequest
 * @package App\Http\Requests
 */
class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'paymentMethodId' => 'required',
            'order'           => ['required',
                Rule::exists('orders', 'id')->where(function ($query) {
                    $query->where('paid', 0);
                }),
            ],
        ];
    }

    /**
     * @param null $keys
     * @return array
     */
    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['order'] = $this->route('order')->id;
        return $data;
    }
}
