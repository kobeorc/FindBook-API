<?php
declare(strict_types=1);

use Faker\Generator as Faker;

$factory->define(\App\Models\Book::class, function (Faker $faker) {
    return [
        'name'        => $faker->name,
        'description' => $faker->realText(),
        'year'        => $faker->year,
        'latitude'    => '55.83' . rand(1, 10000),// Москва запад
        'longitude'   => '37.37' . rand(1, 10000),// Москва запад
        'address'     => $faker->address,
    ];
});
