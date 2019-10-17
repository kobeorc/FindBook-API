<?php
declare(strict_types=1);

use Faker\Generator as Faker;

$factory->define(\App\Models\UserAuthToken::class, function (Faker $faker) {
    return [
        'token'=> $faker->sha256,
        'refresh_token' =>$faker->sha256
    ];
});
