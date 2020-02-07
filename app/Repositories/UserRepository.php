<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use App\User;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class UserRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }
}
