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
        'password'   => '$2y$10$HQbhz5w8azeW2OwkERIoHe45rFwo11aGpNliLbbdREck6WJ/fjzke', // 123456
    ];
});


