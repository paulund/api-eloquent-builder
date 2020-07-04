<?php

use Faker\Generator as Faker;

$factory->define(\Paulund\ApiQueryBuilder\Tests\Mocks\Models\Price::class, function (Faker $faker) {
    return [
        'price' => $faker->randomNumber()
    ];
});