<?php

use Faker\Generator as Faker;

$factory->define(\App\Storage\Form\FormStep::class, function (Faker $faker) {
    return [
        'title' => $faker->realText(80),
        'description' => $faker->realText(),
    ];
});
