<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Creator::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'middle_name'=> $faker->firstNameMale
    ];
});
