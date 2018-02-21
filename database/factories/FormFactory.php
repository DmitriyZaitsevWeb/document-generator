<?php

use Faker\Generator as Faker;

$factory->define(\App\Storage\Form\Form::class, function (Faker $faker) {
    return [
        'title' => $faker->realText(80),
        'guid' => $faker->unique()->slug,
        'description' => $faker->realText(),
    ];
});
