<?php

namespace App\Http\Requests;

use App\Rules\ValidCompanyId;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
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
