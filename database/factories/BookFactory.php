<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Book::class, function (Faker $faker) {
    return [
        'name'        => $faker->name,
        'description' => $faker->realText(),
        'year'        => $faker->year,
        'latitude'    => $faker->latitude,
        'longitude'   => $faker->longitude,
    ];
});
