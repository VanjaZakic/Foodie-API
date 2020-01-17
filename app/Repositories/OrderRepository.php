<?php

namespace App\Repositories;

use App\Order;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class OrderRepository
 * @package App\Repositories
 */
class OrderRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Order::class;
    }
}
