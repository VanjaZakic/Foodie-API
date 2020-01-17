<?php

namespace App\Http\Requests;

use App\Rules\ValidCompanyIdRule;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UserUpdateRequest
 * @package App\Http\Requests
 */
class UserUpdateRequest extends FormRequest
{
    /**
     * @var ValidCompanyIdRule
     */
    private $validCompanyIdRule;

    /**
     * UpdateUserRequest constructor.
     *
     * @param ValidCompanyIdRule $validCompanyIdRule
     * @param array              $query
     * @param array              $request
     * @param array              $attributes
     * @param array              $cookies
     * @param array              $files
     * @param array              $server
     * @param null               $content
     */
    public function __construct(ValidCompanyIdRule $validCompanyIdRule, array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->validCompanyIdRule = $validCompanyIdRule;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        //return $this->user('api')->can('update', $this->user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @return array
     */
    public function requestRules()
    {
        $self = $this;
        return [
            'first_name' => 'required|max:60',
            'last_name'  => 'required|max:60',
            'phone'      => 'required|max:20',
            'address'    => 'required',
            'email'      => 'required|email|max:60',
            'role'       => ['required', Rule::in(User::$roles)],
            'company_id' => [Rule::requiredIf(function () use ($self) {
                return (
                    $this->role == User::ROLE_PRODUCER_ADMIN ||
                    $this->role == User::ROLE_CUSTOMER_ADMIN ||
                    $this->role == User::ROLE_PRODUCER_USER ||
                    $this->role == User::ROLE_CUSTOMER_USER
                );
            })],
        ];
    }

    /**
     * @return array
     */
    public function businessRequestRules()
    {
        return [
            'phone'      => 'unique:users,phone,' . $this->route('user')->id,
            'email'      => 'unique:users,email,' . $this->route('user')->id,
            'role'       => ['required', Rule::in(User::$roles)],
            'company_id' => [$this->validCompanyIdRule->setRequest($this->input())],
        ];
    }
}
