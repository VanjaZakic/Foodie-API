<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Company;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name'    => $faker->company,
        'phone'   => $faker->e164PhoneNumber,
        'address' => $faker->address,
        'email'   => $faker->unique()->safeEmail,
        'image'   => $faker->image(),
        'type'    => 'customer'
    ];
});
