<?php

use Faker\Generator as Faker;

$factory->define(\App\Storage\Form\FormQuestionAnswer::class, function (Faker $faker) {

    $types = ['input', 'select', 'radios', 'checklist', 'textArea'];
    $type = array_random($types);
    $required = (bool)random_int(0, 1);

    $data = [
        'type' => $type,
        'label' => $faker->text(30),
        'model' => $faker->unique()->slug(1),
        'required' => $required
    ];

    if ($data['type'] === 'input') {
        $data['inputType'] = 'text';
    }

    if (in_array($data['type'], ['select', 'radios', 'checklist'])) {
        $count = rand(2, 5);
        $values = $faker->words($count);
        $data['values'] = $values;
        $data['textValues'] = implode(', ', $values);
    }

    return [
        'schema' => serialize($data),
    ];
});
