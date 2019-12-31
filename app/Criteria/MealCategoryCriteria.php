<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class MealCategoryCriteria
 * @package
 */
class MealCategoryCriteria implements CriteriaInterface
{
    /**
     * @var int
     */
    protected $companyId;

    /**
     * MealCategoryCriteria constructor
     *
     * @param int $companyId
     */
    public function __construct(int $companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->where('company_id', '=', $this->companyId);
        return $model;
    }
}
