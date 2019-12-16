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
        $roles = User::$roles;
        foreach ($roles as $role) {
            factory(User::class, 1)->create(['role' => $role]);
        }

        factory(User::class, 1)->create(['role' => User::$roles['ADMIN']]);
        factory(User::class, 2)->create(['role' => User::$roles['PRODUCER_ADMIN']]);
        factory(User::class, 5)->create(['role' => User::$roles['PRODUCER_USER']]);
        factory(User::class, 1)->create(['role' => User::$roles['CUSTOMER_ADMIN']]);
        factory(User::class, 5)->create(['role' => User::$roles['CUSTOMER_USER']]);
        factory(User::class, 10)->create(['role' => User::$roles['USER']]);
    }
}
