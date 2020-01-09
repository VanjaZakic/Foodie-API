<?php


namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class CompanyRepository
 * @package App\Repositories
 */
abstract class AbstractRepository extends BaseRepository
{
    public function paginate($limit = null, $columns = ['*'], $method = "paginate")
    {
        $limit = ($limit > config('fractal.pagination.max_limit', 50)) ? config('fractal.pagination.max_limit', 50) : $limit;
        return parent::paginate($limit, $columns, $method);
    }
}
