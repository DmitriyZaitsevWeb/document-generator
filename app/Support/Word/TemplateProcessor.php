<?php

namespace App\Support\Word;

use App\Support\Word\Parts\Blocks;
use App\Support\Word\Parts\Conditions;
use App\Support\Word\Prepare\Manager;
use Illuminate\Support\Collection;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor as PhpWordTemplateProcessor;

class TemplateProcessor extends PhpWordTemplateProcessor
{
    use Blocks, Conditions;

    CONST EMPTY_VARIABLE_TEMPLATE = "_______";

    /**
     * Template Reader
     *
     * @var \PhpOffice\PhpWord\Reader\Word2007
     */
    private $reader;

    /**
     * Template Writer
     *
     * @var \PhpOffice\PhpWord\Writer\Word2007
     */
    private $writer;

    /**
     * Template attributes
     *
     * @var array
     */
    public $attributes;


    /**
     * TemplateProcessor constructor.
     * @param string $documentTemplate
     * @param array $attributes
     */
    public function __construct($documentTemplate, array $attributes)
    {
        $this->attributes = $attributes;
        $this->reader = IOFactory::load($documentTemplate);
        $this->writer = IOFactory::createWriter($this->reader, 'Word2007');
        parent::__construct($documentTemplate);
    }

    /**
     * Returns array of all variables in template.
     *
     * @return Collection
     */
    public function getVariables()
    {
        /** @var Collection $variables */
        $variables = collect($this->getVariablesForPart($this->tempDocumentMainPart));

        foreach ($this->tempDocumentHeaders as $headerXML) {
            $variables = $variables->merge($this->getVariablesForPart($headerXML));
        }

        foreach ($this->tempDocumentFooters as $footerXML) {
            $variables = $variables->merge($this->getVariablesForPart($footerXML));
        }

        $variables = $variables->filter(
            function ($variable) use ($variables) {
                return strpos($variable, '/') !== 0;
            }
        )->values();

        return $variables->unique();
    }

    /**
     * Finds parts of broken macros and sticks them together.
     * Macros, while being edited, could be implicitly broken by some of the word processors.
     *
     * @param string $documentPart The document part in XML representation
     *
     * @return string
     */
    protected function fixBrokenMacros($documentPart)
    {
        $fixedDocumentPart = $documentPart;

        $fixedDocumentPart = preg_replace_callback(
            '/(\$|\$if)[^{]*\{[^}]*\}/U',
            function ($match) {
                return strip_tags($match[0]);
            },
            $fixedDocumentPart
        );

        return $fixedDocumentPart;
    }

    /**
     * @return $this
     */
    public function prepare()
    {
        (new Manager($this))->prepares()->each(function ($prepare) {
            dispatch($prepare);
        });

        return $this;
    }
}