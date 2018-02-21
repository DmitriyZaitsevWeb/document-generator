<?php

namespace App\Transformers;

use App\Storage\Form\Form;
use App\Storage\User;
use League\Fractal\TransformerAbstract;

class UserAnswersTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param $answer
     * @return array
     */
    public function transform($answer)
    {
        return [
            'id' => $answer->id,
            'data' => unserialize($answer->data)
        ];
    }
}
