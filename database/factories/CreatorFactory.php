<?php
declare(strict_types=1);

use Faker\Generator as Faker;

$factory->define(\App\Models\Creator::class, function (Faker $faker) {
    return [
        'full_name' => $faker->lastName . ' ' . $faker->firstName,
    ];
});
