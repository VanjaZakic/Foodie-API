<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->name,
        'last_name'  => $faker->lastName,
        'phone'      => $faker->e164PhoneNumber,
        'address'    => $faker->address,
        'email'      => $faker->unique()->safeEmail,
        'password'   => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
//        'company_id' => $faker->randomNumber
    ];
});

$factory->state(App\User::class, 'admin', [
    'role' => 1,
]);

$factory->state(App\User::class, 'producer_admin', [
    'role' => 2,
]);

$factory->state(App\User::class, 'producer_user', [
    'role' => 3,
]);

$factory->state(App\User::class, 'customer_admin', [
    'role' => 4,
]);

$factory->state(App\User::class, 'customer_user', [
    'role' => 5,
]);

$factory->state(App\User::class, 'user', [
    'role' => 6,
]);
