<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Book::class, function (Faker $faker) {
    return [
        'name'=> $faker->name,
        'description' => $faker->realText(),
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
    ];
});
