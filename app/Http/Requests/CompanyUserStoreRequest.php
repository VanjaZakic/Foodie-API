<?php

namespace App\Http\Requests;

use App\Rules\ValidCompanyIdRule;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class CompanyUserStoreRequest
 * @package App\Http\Requests
 * @property int    $id
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property string $email
 * @property string $image
 * @property string type
 * @property int    $lat
 * @property int    $lng
 */
class CompanyUserStoreRequest extends FormRequest
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
        return [
            'first_name' => 'required|max:60',
            'last_name'  => 'required|max:60',
            'phone'      => 'required|max:20',
            'address'    => 'required',
            'email'      => 'required|email|max:60',
            'password'   => 'required|confirmed',
            'role'       => ['required', Rule::in([User::ROLE_PRODUCER_ADMIN, User::ROLE_CUSTOMER_ADMIN])]
        ];
    }

    /**
     * @return array
     */
    public function businessRequestRules()
    {
        return [
            'phone'      => 'unique:users',
            'email'      => 'unique:users',
            'company_id' => $this->validCompanyIdRule->setCompanyId($this->route('company')->id)
        ];
    }

    /**
     * @return bool
     */
    public function isAuthorized()
    {
        return true;
    }
}
