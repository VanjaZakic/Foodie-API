<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class CompanyUserStoreRequest
 * @package App\Http\Requests
 */
class CompanyUserStoreRequest extends FormRequest
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
            'first_name' => 'required|max:60',
            'last_name'  => 'required|max:60',
            'phone'      => 'required|max:20',
            'address'    => 'required',
            'email'      => 'email|required|unique:users|max:60',
            'password'   => 'required|confirmed',
            'role'       => ['required', Rule::in([User::ROLE_PRODUCER_ADMIN, User::ROLE_CUSTOMER_ADMIN])]
        ];
    }
}
