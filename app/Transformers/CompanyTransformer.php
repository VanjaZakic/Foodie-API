<?php

namespace App\Transformers;

use App\Company;
use League\Fractal\TransformerAbstract;

/**
 * Class CompanyTransformer
 * @package App\Transformers
 */
class CompanyTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Company $company
     *
     * @return array
     */
    public function transform(Company $company)
    {
        return [
            'id'      => $company->id,
            'name'    => $company->name,
            'phone'   => $company->phone,
            'address' => $company->address,
            'email'   => $company->email,
            'image'   => $company->image,
            'lat'     => $company->lat,
            'lng'     => $company->lng
        ];
    }
}
