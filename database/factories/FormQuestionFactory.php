<?php

use Faker\Generator as Faker;

$factory->define(\App\Storage\Form\FormQuestion::class, function (Faker $faker) {
    $multiple = (bool)random_int(0, 1);

    return [
        'question' => $faker->realText(80),
        'help_text' => $faker->realText(),
        'required' => 'No',
        'multiple' => $multiple,
        'name' => $multiple ? $faker->unique()->slug(1) : ''
    ];
});
