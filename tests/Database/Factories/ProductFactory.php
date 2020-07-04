<?php

use Faker\Generator as Faker;

$factory->define(\Paulund\ApiQueryBuilder\Tests\Mocks\Models\Product::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});