<?php

namespace App\Http\Requests;

use App\Company;
use App\Rules\ValidCompanyTypeRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CompanyUpdateRequest
 * @package App\Http\Requests
 * @property int     $id
 * @property string  $name
 * @property string  $phone
 * @property string  $address
 * @property string  $email
 * @property string  $image
 * @property string  type
 * @property int     $lat
 * @property int     $lng
 * @property Company company
 */
class CompanyUpdateRequest extends FormRequest
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
            'phone'   => 'required|max:20|unique:companies,phone,' . $this->route('company')->id,
            'address' => 'required',
            'email'   => 'email|required|max:60|unique:companies,email,' . $this->route('company')->id,
            'image'   => 'required|image|mimes:jpeg,jpg,png,gif|max:10000',
            'type'    => ['required', new ValidCompanyTypeRule($this->company)],
            'lat'     => 'sometimes|numeric|between:-90,90',
            'lng'     => 'sometimes|numeric|between:-180,80'
        ];
    }
}
