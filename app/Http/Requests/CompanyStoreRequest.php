<?php

namespace App\Http\Requests;

use App\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class CompanyStoreRequest
 * @property int    $id
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property string $email
 * @property string $image
 * @property string type
 * @property int    $lat
 * @property int    $lng
 * @package App\Http\Requests
 */
class CompanyStoreRequest extends FormRequest
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
            'name'    => 'required|max:60',
            'phone'   => 'required|unique:companies|max:20',
            'address' => 'required',
            'email'   => 'email|required|unique:companies|max:60',
            'image'   => 'required|image|mimes:jpeg,jpg,png,gif|max:10000',
            'type'    => ['required', Rule::in([Company::TYPE_PRODUCER, Company::TYPE_CUSTOMER])],
            'lat'     => 'sometimes|numeric|between:-90,90',
            'lng'     => 'sometimes|numeric|between:-180,80'
        ];
    }
}
