<?php

namespace App\Http\Requests;

use App\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class StoreCompanyRequest
 * @package App\Http\Requests
 */
class StoreCompanyRequest extends FormRequest
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
            'name'    => 'required',
            'phone'   => 'required|unique:companies',
            'address' => 'required',
            'email'   => 'email|required|unique:companies',
            'image'   => 'required',
            'type'    => ['required', Rule::in([Company::TYPE_PRODUCER, Company::TYPE_CUSTOMER])],
            'lat'     => 'sometimes|numeric|between:-90,90',
            'lng'     => 'sometimes|numeric|between:-180,80'
        ];
    }
}
