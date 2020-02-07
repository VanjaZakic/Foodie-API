<?php

/** @var Factory $factory */

use App\Company;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Http\UploadedFile;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name'    => $faker->company,
        'phone'   => $faker->e164PhoneNumber,
        'address' => $faker->address,
        'email'   => $faker->unique()->safeEmail,
        'image'   => UploadedFile::fake()->image('avatar.jpg'),
        'type'    => Company::TYPE_PRODUCER
    ];
});

$factory->state(App\Company::class, COMPANY::TYPE_PRODUCER, [
    'type' => COMPANY::TYPE_PRODUCER,
]);

$factory->state(App\Company::class, COMPANY::TYPE_CUSTOMER, [
    'type' => COMPANY::TYPE_CUSTOMER,
]);
