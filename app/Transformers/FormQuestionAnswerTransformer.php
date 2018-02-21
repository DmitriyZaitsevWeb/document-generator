<?php

namespace App\Transformers;

use App\Storage\Form\FormQuestionAnswer;
use League\Fractal\TransformerAbstract;

class FormQuestionAnswerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param FormQuestionAnswer $formQuestionAnswer
     * @return array
     */
    public function transform(FormQuestionAnswer $formQuestionAnswer)
    {
        $schema = unserialize($formQuestionAnswer->schema);

        if(isset($schema['required'])){
            $schema['required'] = (bool) $schema['required'];
        }

        return [
            'id' => $formQuestionAnswer->id,
            'form_question_id' => $formQuestionAnswer->form_question_id,
            'schema' => $schema,
        ];
    }

}
