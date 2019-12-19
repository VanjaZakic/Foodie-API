<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CompanyUsersCriteria.
 *
 * @package namespace App\Criteria;
 */
class CompanyUsersCriteria implements CriteriaInterface
{
    /**
     * @var
     */
    protected $companyId;

    /**
     * CompanyUsersCriteria constructor.
     *
     * @param $companyId
     */
    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->where('company_id', '=', $this->companyId);
        return $model;
    }
}
