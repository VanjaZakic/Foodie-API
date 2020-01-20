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
            'meals.*.meal_id' => ['required',
                Rule::exists('meal_categories')->where(function ($query) {
                    $query->where('company_id', 1);
                }),
            ],
//             'meals.*.meal_id' => ['required',
//                 Rule::exists('meals', 'id')->where(function ($query) {
//                     $query->where(Meal::with('mealCategory')->where('company_id')->get(), 1);
//                 }),
//             ],
            'meals.*.quantity' => 'required|integer',
        ];
    }
}
