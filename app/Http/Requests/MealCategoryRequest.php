<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class MealCategoryRequest
 * @package App\Http\Requests
 *
 * @property int    $id
 * @property string $name
 * @property string $image
 * @property int    $company_id
 */
class MealCategoryRequest extends FormRequest
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
            'name'  => 'required|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10000',
        ];
    }
}
