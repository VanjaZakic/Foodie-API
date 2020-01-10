<?php


namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class CompanyRepository
 * @package App\Repositories
 */
abstract class AbstractRepository extends BaseRepository
{
    /**
     * @param null   $limit
     * @param array  $columns
     * @param string $method
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*'], $method = "paginate")
    {
        $limit = ($limit > config('repository.pagination.max_limit', 50)) ? config('repository.pagination.max_limit', 50) : $limit;
        return parent::paginate($limit, $columns, $method);
    }
}
