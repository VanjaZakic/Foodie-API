<?php

namespace App\Rules;

use App\Company;
use App\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ValidCompanyId implements Rule
{
    protected $request;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->request->role == User::ROLE_PRODUCER_ADMIN) {
            $company        = Company::find($this->request->company_id);
            $producer_admin = User::where('company_id', $this->request->company_id)->where('role', User::ROLE_PRODUCER_ADMIN)->get();

            if (!$company || $company->type != Company::TYPE_PRODUCER || count($producer_admin)) {
                return false;
            }
        }

        if ($this->request->role == User::ROLE_CUSTOMER_ADMIN) {
            $company        = Company::find($this->request->company_id);
            $customer_admin = User::where('company_id', $this->request->company_id)->where('role', User::ROLE_PRODUCER_ADMIN)->get();

            if (!$company || $company->type != Company::TYPE_CUSTOMER || count($customer_admin)) {
                return false;
            }
        }

        if ($this->request->role == User::ROLE_PRODUCER_USER) {
            $company = Company::find($this->request->company_id);

            if (!$company || $company->type != Company::TYPE_PRODUCER) {
                return false;
            }
        }

        if ($this->request->role == User::ROLE_CUSTOMER_USER) {
            $company = Company::find($this->request->company_id);

            if (!$company || $company->type != Company::TYPE_CUSTOMER) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid company id.';
    }
}
