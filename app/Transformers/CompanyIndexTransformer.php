<?php

namespace App\Transformers;

use App\Company;
use League\Fractal\TransformerAbstract;

/**
 * Class CompanyTransformer
 * @package App\Transformers
 */
class CompanyIndexTransformer extends TransformerAbstract
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
            'id'    => $company->id,
            'name'  => $company->name,
            'links' => [
                'rel' => 'company',
                'uri' => '/companies/' . $company->id,
            ],

        ];
    }
}
