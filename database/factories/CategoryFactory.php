<?php

use Faker\Generator as Faker;

$factory->define(\App\Storage\Form\Category::class, function (Faker $faker) {
    return [
        'title' => $faker->text(30),
        'guid' => $faker->unique()->slug,
        'description' => $faker->realText(),
    ];
});
