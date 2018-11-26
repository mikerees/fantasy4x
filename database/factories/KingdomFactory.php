<?php

use Faker\Generator as Faker;

$factory->define(App\Entities\Kingdom::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'race' => $faker->firstName,
        'class' => 'App\Models\Kingdom',
        'ticks' => 0
    ];
});
