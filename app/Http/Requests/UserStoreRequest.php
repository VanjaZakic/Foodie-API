<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UserStoreRequest
 * @package App\Http\Requests
 * @property int    $id
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property string $address
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $company_id
 */
class UserStoreRequest extends FormRequest
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
            'first_name' => 'required|max:60',
            'last_name'  => 'required|max:60',
            'phone'      => 'required|max:20|unique:users',
            'address'    => 'required',
            'email'      => 'email|required||max:60|unique:users',
            'password'   => 'required|confirmed',
            'role'       => ['required', Rule::in([User::ROLE_PRODUCER_USER, User::ROLE_CUSTOMER_USER, User::ROLE_USER])],
            'company_id' => Rule::requiredIf(function () use ($self) {
                return $self->role == User::ROLE_PRODUCER_USER || $self->role == User::ROLE_CUSTOMER_USER;
            })
        ];
    }
}
