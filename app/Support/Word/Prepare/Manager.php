<?php

namespace App\Support\Word\Prepare;

use App\Support\Word\Prepare\Data\Blocks;
use App\Support\Word\Prepare\Data\Conditions;
use App\Support\Word\Prepare\Data\Variables;
use App\Support\Word\TemplateProcessor;

class Manager
{

    /**
     * @var TemplateProcessor
     */
    protected $templateProcessor;

    /**
     * Manager constructor.
     * @param TemplateProcessor $templateProcessor
     */
    public function __construct(TemplateProcessor $templateProcessor)
    {
        $this->templateProcessor = $templateProcessor;
    }

    /**
     * Get available prepares.
     *
     * @return \Illuminate\Support\Collection
     */
    public function prepares()
    {
        return collect([
            Conditions::class,
            Blocks::class,
            Variables::class
        ])->map(function ($class) {
            return new $class($this->templateProcessor);
        });
    }

}