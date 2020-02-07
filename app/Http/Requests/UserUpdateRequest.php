<?php

namespace App\Http\Requests;

use App\Rules\ValidCompanyIdRule;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UserUpdateRequest
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
 * @property User   user
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
            'company_id' => $this->validCompanyIdRule->setCompanyId($this->company_id),
        ];
    }

    /**
     * @return mixed
     */
    public function isAuthorized()
    {
        return $this->user()->can('update', $this->user);
    }
}
