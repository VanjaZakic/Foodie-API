<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class StoreUserRequest
 * @package App\Http\Requests
 */
class StoreUserRequest extends FormRequest
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
        $self = $this;
        return [
            'first_name' => 'required',
            'last_name'  => 'required',
            'phone'      => 'required',
            'address'    => 'required',
            'email'      => 'email|required|unique:users',
            'password'   => 'required|confirmed',
            'role'       => ['required', Rule::in([User::ROLE_PRODUCER_USER, User::ROLE_CUSTOMER_USER, User::ROLE_USER])],
            'company_id' => Rule::requiredIf(function () use ($self) {
                return $self->role == User::ROLE_PRODUCER_USER || $self->role == User::ROLE_CUSTOMER_USER;
            })
        ];
    }
}
