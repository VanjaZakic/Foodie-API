<?php

use App\User;
use Illuminate\Database\Seeder;

/**
 * Class UsersTableSeeder
 */
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 1)->states('admin')->create();
        factory(User::class, 1)->states('producer_admin')->create();
        factory(User::class, 1)->states('producer_user')->create();
        factory(User::class, 1)->states('customer_admin')->create();
        factory(User::class, 1)->states('customer_user')->create();
        factory(User::class, 1)->states('user')->create();
    }
}
