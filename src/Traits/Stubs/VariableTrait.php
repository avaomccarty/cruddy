<?php

namespace Cruddy\Traits\Stubs;

use Illuminate\Support\Str;

trait VariableTrait
{
    /**
     * The acceptable value placeholders within a stub.
     *
     * @var array
     */
    protected $stubValuePlaceholders = [
        'DummyValue',
        '{{ value }}',
        '{{value}}'
    ];

    /**
     * The acceptable collection of variable placeholders within a stub.
     *
     * @var array
     */
    protected $stubVariableCollectionPlaceholders = [
        'DummyVariableCollection',
        '{{ variableCollection }}',
        '{{variableCollection}}'
    ];

    /**
     * The acceptable variable placeholders within a stub.
     *
     * @var array
     */
    protected $stubVariablePlaceholders = [
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
    protected function replaceVariableCollectionPlaceholders(string $variable, string &$stub) : self
    {
        $stub = str_replace($this->stubVariableCollectionPlaceholders, $this->getCamelCasePlural($variable), $stub);

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
        $stub = str_replace($this->stubVariablePlaceholders, $this->getCamelCaseSingular($variable), $stub);

        return $this;
    }
}