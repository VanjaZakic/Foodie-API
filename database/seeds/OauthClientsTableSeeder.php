<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OauthClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_clients')->insert([
            'id'                     => 2,
            'user_id'                => null,
            'name'                   => 'Laravel Password Grant Client',
            'secret'                 => config('auth.passport.client_secret'),
            'redirect'               => 'http://localhost',
            'personal_access_client' => 0,
            'password_client'        => 1,
            'revoked'                => 0
        ]);
    }
}
