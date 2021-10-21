<?php

namespace Cruddy\Traits\Stubs;

trait StubTrait
{
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
        if ($this->needsFormattingRemoved($value)) {
            $value = substr($value, 0, -strlen($this->getEndOfLine()));
        }
    }

    /**
     * Determine if the value need formatting removed from the end.
     *
     * @param  string  $value
     * @return boolean
     */
    protected function needsFormattingRemoved(string $value) : bool
    {
        return substr($value, -strlen($this->getEndOfLine())) === $this->getEndOfLine();
    }

    /**
     * Replace the variables with the correct value within the stub.
     *
     * @param  array  $variables
     * @param  string  $value
     * @param  string  &$stub
     * @return void
     */
    protected function replaceInStub(array $variables, string $value, string &$stub) : void
    {
        $stub = str_replace($variables, $value, $stub);
    }

}