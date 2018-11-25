<?php

use Faker\Generator as Faker;

$factory->define(App\Entities\Kingdom::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'user_id' => $faker->numberBetween(),
        'race' => $faker->firstName,
        'ticks' => $faker->numberBetween()
    ];
});
