<?php

namespace App\Transformers;

use App\Storage\Form\FormAnswer;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class FormAnswerTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['form'];

    /**
     * A Fractal transformer.
     *
     * @param FormAnswer $answer
     * @return array
     */
    public function transform(FormAnswer $answer)
    {
        return [
            'id' => $answer->id,
            'data' => unserialize($answer->data),
            'created_at' => $answer->created_at->diffForHumans(),
        ];
    }

    /**
     * @param FormAnswer $answer
     * @return \League\Fractal\Resource\Item
     */
    public function includeForm(FormAnswer $answer)
    {
        return $this->item($answer->form, new FormTransformer());
    }

}
