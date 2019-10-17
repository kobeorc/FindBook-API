<?php
declare(strict_types=1);

use Faker\Generator as Faker;

$factory->define(\App\Models\Image::class, function (Faker $faker) {
    return [
        'path' => $faker->imageUrl()
    ];
});
