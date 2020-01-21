<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class OrderRequest
 * @package App\Http\Requests
 */
class OrderRequest extends FormRequest
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
            'delivery_datetime' => 'required|after:1 hour',
            'company_id'        => ['sometimes',
                Rule::exists('companies', 'id')->where(function ($query) {
                    $query->where('type', 'producer');
                }),
            ],
            'meals.*.meal_id' => 'required|exists:meals,id',
            'meals.*.quantity' => 'required|integer',
        ];
    }
}
