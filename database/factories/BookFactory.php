<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Book::class, function (Faker $faker) {
    return [
        'name'        => $faker->name,
        'description' => $faker->realText(),
        'year'        => $faker->year,
        'latitude'    => '55.75' . rand(1, 10000),
        'longitude'   => '37.62' . rand(1, 10000),
    ];
});
