<?php

/** @var Factory $factory */

use App\Company;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->name,
        'last_name'  => $faker->lastName,
        'phone'      => $faker->e164PhoneNumber,
        'address'    => $faker->address,
        'email'      => $faker->unique()->safeEmail,
        'password'   => '123456',
        'role'       => USER::ROLE_USER
    ];
});

$factory->state(User::class, 'admin', [
    'role'       => USER::ROLE_ADMIN,
    'company_id' => null
]);

$factory->state(User::class, 'producer_admin', [
    'role'       => USER::ROLE_PRODUCER_ADMIN,
    'company_id' => function () {
        return factory(Company::class)->states('producer')->create()->id;
    },
]);

$factory->state(User::class, 'customer_admin', [
    'role'       => USER::ROLE_CUSTOMER_ADMIN,
    'company_id' => function () {
        return factory(Company::class)->states('customer')->create()->id;
    },
]);

$factory->state(User::class, 'producer_user', [
    'role'       => USER::ROLE_PRODUCER_USER,
    'company_id' => function () {
        return factory(Company::class)->states('producer')->create()->id;
    },
]);

$factory->state(User::class, 'customer_user', [
    'role'       => USER::ROLE_CUSTOMER_USER,
    'company_id' => function () {
        return factory(Company::class)->states('customer')->create()->id;
    },
]);

$factory->state(User::class, 'user', [
    'role'       => USER::ROLE_USER,
    'company_id' => null
]);
