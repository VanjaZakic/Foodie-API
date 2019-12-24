<?php

/** @var Factory $factory */

use App\Company;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name'    => $faker->company,
        'phone'   => $faker->e164PhoneNumber,
        'address' => $faker->address,
        'email'   => $faker->unique()->safeEmail,
        'image'   => $faker->image(),
        'type'    => 'producer'
    ];
});

