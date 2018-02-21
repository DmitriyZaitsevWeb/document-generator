<?php

namespace App\Transformers;

use App\Storage\Form\FormStep;
use League\Fractal\TransformerAbstract;

class FormStepTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['questions'];
    protected $availableIncludes = [];

    /**
     * A Fractal transformer.
     *
     * @param FormStep $formStep
     * @return array
     */
    public function transform(FormStep $formStep)
    {
        return [
            'id' => $formStep->id,
            'title' => $formStep->title,
            'description' => $formStep->description,
            'order' => $formStep->order,
        ];
    }

    /**
     * @param FormStep $formStep
     * @return \League\Fractal\Resource\Collection
     */
    public function includeQuestions(FormStep $formStep)
    {
        return $this->collection($formStep->questions->sortBy(function ($value){ return $value->order ? $value->order : $value->id;}), new FormQuestionTransformer(), false);
    }
}
