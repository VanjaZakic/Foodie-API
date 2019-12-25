<?php

namespace App\Http\Requests;

use App\Rules\ValidCompanyId;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Class UserRequest
 * @package App\Http\Requests
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();
        switch ($user->role) {
            case User::ROLE_ADMIN:
                return $user->canAdminUpdateUser($this);
            case User::ROLE_PRODUCER_ADMIN:
                return $user->canProducerAdminUpdateUser($this);
            case User::ROLE_CUSTOMER_ADMIN:
                return $user->canCustomerAdminUpdateUser($this);
            case User::ROLE_PRODUCER_USER:
                return $user->canProducerUserUpdateUser($this);
            case User::ROLE_CUSTOMER_USER:
                return $user->canCustomerUserUpdateUser($this);
            case User::ROLE_USER:
                return $user->canUserUpdateUser($this);
            default:
                return false;
        }
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
            'phone'      => 'required|unique:users,phone,' . $this->route('user')->id,
            'address'    => 'required',
            'email'      => 'required|email|unique:users,email,' . $this->route('user')->id,
            'role'       => ['required', Rule::in(User::$roles)],
            'company_id' => [Rule::requiredIf(function () use ($self) {
                return $self->role != User::ROLE_ADMIN && $self->role != User::ROLE_USER;
            }), new ValidCompanyId($this)],
        ];
    }
}
