<?php

namespace App\Support\Word\Parts;

use Illuminate\Support\Collection;

trait Blocks
{
    /**
     * Returns array of all blocks in template.
     *
     * @return Collection
     */
    public function getBlocks(): Collection
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
                return $variables->contains('/' . $variable);
            }
        )->values();

        return $variables->unique();
    }

    /**
     * @param $blockName
     * @return string[]
     */
    public function getBlockVariables($blockName)
    {
        preg_match(
            '/(<\?xml.*)(<w:p.*>\${' . $blockName . '}<\/w:.*?p>)(.*)(<w:p.*\${\/' . $blockName . '}<\/w:.*?p>)/is',
            $this->tempDocumentMainPart,
            $matches
        );

        return isset($matches[3]) ? $this->getVariablesForPart($matches[3]) : [];
    }
}