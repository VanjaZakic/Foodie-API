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
        $key   = array_search(User::ROLE_ADMIN, $roles);
        unset($roles[$key]);

        $password = bcrypt('123456');

        $users = factory(User::class, 1)->raw([
            'role'     => User::ROLE_ADMIN,
            'password' => $password
        ]);

        for ($i = 0; $i < 50000; $i++) {
            $random_keys = array_rand($roles, 1);
            $tempUsers   = factory(User::class, 1)->raw([
                'role'     => $roles[$random_keys],
                'password' => $password
            ]);

            $users = array_merge($users, $tempUsers);
            if (count($users) == 9362) {
                User::insert($users);
                $users = [];
            }
        }
        User::insert($users);
    }
}
