<?php

namespace App\Rules;

use App\Company;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class ValidCompanyType
 * @package App\Rules
 */
class ValidCompanyTypeRule implements Rule
{
    /**
     * @var
     */
    protected $company;

    /**
     * Create a new rule instance.
     *
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($value != $this->company->type) {
            return false;
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
        return 'Invalid company type.';
    }
}
