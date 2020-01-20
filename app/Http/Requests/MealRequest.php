<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class MealRequest
 * @package App\Http\Requests
 */
class MealRequest extends FormRequest
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
            'name'             => 'required|max:255',
            'image'            => 'required|image|mimes:jpeg,png,jpg,gif|max:10000',
            'price'            => 'required|numeric|not_in:0',
            'meal_category_id' => ['required',
                Rule::exists('meal_categories', 'id')->where(function ($query) {
                    $query->where('company_id', auth()->user()->company_id);
                }),
            ],
        ];
    }
}
