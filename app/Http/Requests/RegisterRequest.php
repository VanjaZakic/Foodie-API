<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;

class RegisterRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed',
            'role' => 'required',
        ];
    }

    public function populateUser()
    {
        $user = new User();
        $user->first_name = $this->get('first_name');
        $user->last_name = $this->get('last_name');
        $user->phone = $this->get('phone');
        $user->address = $this->get('address');
        $user->email = $this->get('email');
        $user->password = bcrypt($this->get('password'));
        $user->role = $this->get('role');
        return $user;
    }
}
