<?php

namespace App\Criteria;

use App\Company;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CompanyCriteria.
 *
 * @package namespace App\Criteria;
 */
class CompanyCriteria implements CriteriaInterface
{
    /**
     * @var Company
     */
    protected $company;

    /**
     * CompanyCriteria constructor
     *
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }
    
    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('company_id', '=', $this->company->id);
    }
}
