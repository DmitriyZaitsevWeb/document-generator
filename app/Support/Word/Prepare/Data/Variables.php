<?php

namespace App\Support\Word\Prepare\Data;

use App\Support\Word\Prepare\BasePrepare;

class Variables extends BasePrepare
{
    /**
     * @return mixed
     */
    public function handle()
    {
        $attributes = $this->templateProcessor->attributes;
        $variables = $this->templateProcessor->getVariables();

        foreach ($variables as $variable) {
            $answer = $this->templateProcessor::EMPTY_VARIABLE_TEMPLATE;
            if (array_has($attributes, $variable)) {
                $answer = $attributes[$variable];
                if (is_array($answer)) {
                    $answer = implode(', ', $answer);

                }
            }
            $this->templateProcessor->setValue($variable, $answer);
        }

    }
}