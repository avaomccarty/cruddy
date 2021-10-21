<?php

namespace Cruddy\Traits\Stubs;

use Illuminate\Support\Str;

trait VariableTrait
{
    use StubTrait;

    /**
     * The acceptable value placeholders within a stub.
     *
     * @var array
     */
    protected $valuePlaceholders = [
        'DummyValue',
        '{{ value }}',
        '{{value}}'
    ];

    /**
     * The acceptable collection of variable placeholders within a stub.
     *
     * @var array
     */
    protected $variableCollectionPlaceholders = [
        'DummyVariableCollection',
        '{{ variableCollection }}',
        '{{variableCollection}}'
    ];

    /**
     * The acceptable variable placeholders within a stub.
     *
     * @var array
     */
    protected $variablePlaceholders = [
        'DummyVariable',
        '{{ variable }}',
        '{{variable}}'
    ];

    /**
     * Get the studly singular version of the string with the first character lower-case.
     *
     * @param  string  $value
     * @return string
     */
    protected function getCamelCaseSingular(string $value) : string
    {
        return lcfirst(Str::studly(Str::singular(trim($value)))) ?? '';
    }

    /**
     * Get the studly plural version of the string with the first character lower-case.
     *
     * @param  string  $value
     * @return string
     */
    protected function getCamelCasePlural(string $value) : string
    {
        return lcfirst(Str::pluralStudly(trim($value))) ?? '';
    }
            
    /**
     * Replace the variable collection placeholders within a stub.
     *
     * @param  string  $variable
     * @param  string  &$stub
     * @return self
     */
    protected function replaceValuePlaceholders(string $variable, string &$stub) : self
    {
        $this->replaceInStub($this->valuePlaceholders, $variable, $stub);

        return $this;
    }
            
    /**
     * Replace the variable collection placeholders within a stub.
     *
     * @param  string  $variable
     * @param  string  &$stub
     * @return self
     */
    protected function replaceVariableCollectionPlaceholders(string $variable, string &$stub) : self
    {
        $value = $this->getCamelCasePlural($variable);
        $this->replaceInStub($this->variableCollectionPlaceholders, $value, $stub);

        return $this;
    }
            
    /**
     * Replace the variable placeholders within a stub.
     *
     * @param  string  $variable
     * @param  string  &$stub
     * @return self
     */
    protected function replaceVariablePlaceholders(string $variable, string &$stub) : self
    {
        $this->replaceInStub($this->variablePlaceholders, $this->getCamelCaseSingular($variable), $stub);

        return $this;
    }
}