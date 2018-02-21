<?php

namespace App\Transformers;

use App\Storage\Form\FormQuestion;
use League\Fractal\TransformerAbstract;

class FormQuestionTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['answers'];
    protected $availableIncludes = [];

    /**
     * A Fractal transformer.
     *
     * @param FormQuestion $formQuestion
     * @return array
     */
    public function transform(FormQuestion $formQuestion)
    {
        return [
            'id' => $formQuestion->id,
            'form_step_id' => $formQuestion->form_step_id,
            'question' => $formQuestion->question,
            'name' => $formQuestion->name,
            'help_text' => $formQuestion->help_text,
            'attributes' => $formQuestion->attributes,
            'required' => $formQuestion->required,
            'multiple' => (bool) $formQuestion->multiple,
        ];
    }

    /**
     * @param FormQuestion $formQuestion
     * @return \League\Fractal\Resource\Collection
     */
    public function includeAnswers(FormQuestion $formQuestion)
    {
        return $this->collection($formQuestion->answers->sortBy(function ($value){ return $value->order ? $value->order : $value->id;}), new FormQuestionAnswerTransformer(), false);
    }
}
