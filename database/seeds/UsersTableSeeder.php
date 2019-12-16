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
        unset($roles[0]);

        factory(User::class, 1)->create([
            'role'     => User::ROLE_ADMIN,
            'password' => '123456'
        ]);

        for ($i = 0; $i < 20; $i++) {
            $random_keys = array_rand($roles, 1);
            factory(User::class, 1)->create([
                'role'     => $roles[$random_keys],
                'password' => '123456'
            ]);
        }
    }
}
