<?php

namespace Cruddy\Traits\Stubs;

use Cruddy\Traits\ConfigTrait;

trait StubTrait
{
    use ConfigTrait;

    /**
     * The default end of line formatting.
     *
     * @var string
     */
    protected $defaultEndOfLine = "\n\t\t";

    /**
     * Get the end of line formatting.
     *
     * @return string
     */
    protected function getEndOfLine() : string
    {
        if (isset($this->endOfLine)) {
            return $this->endOfLine;
        }

        return $this->defaultEndOfLine;
    }

    /**
     * Remove the formatting from the end of the value.
     *
     * @param  string  &$value
     * @return void
     */
    protected function removeEndOfLineFormatting(string &$value) : void
    {
        if ($this->hasEndOfLineFormatting($value)) {
            $value = substr($value, 0, -strlen($this->getEndOfLine()));
        }
    }

    /**
     * Add the formatting to the end of the value.
     *
     * @param  string  &$value
     * @return void
     */
    protected function addEndOfLineFormatting(string &$value) : void
    {
        if (!$this->hasEndOfLineFormatting($value)) {
            $value .= $this->getEndOfLine();
        }
    }

    /**
     * Determine if the value need formatting removed from the end.
     *
     * @param  string  $value
     * @return boolean
     */
    protected function hasEndOfLineFormatting(string $value) : bool
    {
        return substr($value, -strlen($this->getEndOfLine())) === $this->getEndOfLine();
    }

    /**
     * Replace the variables with the correct value within the stub.
     *
     * @param  array  $variables
     * @param  string  $value
     * @param  string  &$stub
     * @return self
     */
    protected function replaceInStub(array $variables, string $value, string &$stub) : self
    {
        $stub = str_replace($variables, $value, $stub);

        return $this;
    }

}