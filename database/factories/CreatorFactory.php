<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Creator::class, function (Faker $faker) {
    return [
        'first_name' => $firstName = $faker->firstName,
        'last_name' => $lastName = $faker->lastName,
        'middle_name'=> $firstNameMale = $faker->firstNameMale,
        'full_name' => $firstName.' '.$lastName.' '.$firstNameMale,
    ];
});
