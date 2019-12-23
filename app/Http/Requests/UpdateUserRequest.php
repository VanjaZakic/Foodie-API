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
        switch (Auth::user()->role) {
            case User::ROLE_ADMIN:
                return $this->canAdminUpdateUser();
            case User::ROLE_PRODUCER_ADMIN:
                return $this->canProducerAdminUpdateUser();
            case User::ROLE_CUSTOMER_ADMIN:
                return $this->canCustomerAdminUpdateUser();
            case User::ROLE_PRODUCER_USER:
                return $this->canProducerUserUpdateUser();
            case User::ROLE_CUSTOMER_USER:
                return $this->canCustomerUserUpdateUser();
            case User::ROLE_USER:
                return $this->canUserUpdateUser();
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

    public function canAdminUpdateUser()
    {
        if ($this->user->role == User::ROLE_ADMIN && ($this->role != User::ROLE_ADMIN || $this->company_id != $this->user->company_id)) {
            return false;
        }

        return true;
    }

    public function canProducerAdminUpdateUser()
    {
        return (Auth::user()->id == $this->user->id && $this->role == $this->user->role && $this->company_id == $this->user->company_id) ||
            ($this->user->role == User::ROLE_PRODUCER_USER && $this->role == User::ROLE_USER && $this->company_id == null);
    }

    public function canCustomerAdminUpdateUser()
    {
        return (Auth::user()->id == $this->user->id && $this->role == $this->user->role && $this->company_id == $this->user->company_id) ||
            ($this->user->role == User::ROLE_CUSTOMER_USER && $this->role == User::ROLE_USER && $this->company_id == null);
    }

    public function canProducerUserUpdateUser()
    {
        return Auth::user()->id == $this->user->id && $this->role == $this->user->role && $this->company_id == $this->user->company_id;
    }

    public function canCustomerUserUpdateUser()
    {
        return Auth::user()->id == $this->user->id && $this->role == $this->user->role && $this->company_id == $this->user->company_id;
    }

    public function canUserUpdateUser()
    {
        return Auth::user()->id == $this->user->id && $this->role == $this->user->role && $this->company_id == $this->user->company_id;
    }
}
