<?php


namespace App\Repositories;

use App\Company;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class CompanyRepository
 * @package App\Repositories
 */
class CompanyRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Company::class;
    }
}
