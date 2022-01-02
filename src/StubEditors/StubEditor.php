<?php

namespace Cruddy\StubEditors;

use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\ConfigTrait;
use Illuminate\Support\Facades\File;

abstract class StubEditor
{
    use ConfigTrait, CommandTrait;

    /**
     * The acceptable input placeholders within a stub.
     *
     * @var array
     */
    public $inputPlaceholders = [
        'DummyInputs',
        '{{ inputs }}',
        '{{inputs}}'
    ];

    /**
     * The formatting at the end of the line.
     *
     * @var string
     */
    protected $endOfLine = "\n\t\t\t\t";

    /**
     * The default end of line formatting.
     *
     * @var string
     */
    protected $defaultEndOfLine = "\n\t\t";

    /**
     * The placeholders used within the stub.
     *
     * @var array
     */
    protected $placeholders = [];

    /**
     * The stub to be edited.
     *
     * @var string
     */
    protected $stub = '';

    /**
     * Get the default types.
     *
     * @return array
     */
    protected function getDefaultTypes() : array
    {
        return [
            'index',
            'create',
            'show',
            'edit',
         ];
    }

    /**
     * Determine if the type is valid.
     *
     * @param  string  $type = ''
     * @return boolean
     */
    protected function isValidType(string $type = '') : bool
    {
        return in_array($type, $this->getDefaultTypes());
    }

    /**
     * The constructor method.
     *
     * @param  string  &$stub = ''
     * @return void
     */
    public function __construct(string &$stub = '')
    {
        $this->stub = $stub ?? $this->getStubFile();
    }

    /**
     * Get the stub file.
     *
     * @return string
     */
    abstract public function getStubFile() : string;

    /**
     * Get the stub.
     *
     * @return string
     */
    public function getStub() : string
    {
        return $this->stub;
    }

    /**
     * Replace the variables with the correct value within the stub.
     *
     * @param  array  $variables
     * @param  string  $value = ''
     * @param  string  ?&$stub = null
     * @return self
     */
    public function replaceInStub(array $variables, string $value = '', ?string &$stub = null) : self
    {
        if (is_null($stub)) {
            $this->replaceInStubProperty($variables, $value);
        } else {
            $this->replaceInProvidedStub($variables, $value, $stub);
        }


        return $this;
    }

    /**
     * Replace the variables with the correct value within the stub property.
     *
     * @param  array  $variables
     * @param  string  $value
     * @return 
     */
    protected function replaceInStubProperty(array $variables, string $value) : void
    {
        $this->stub = str_replace($variables, $value, $this->stub);
    }

    /**
     * Replace the variables with the correct value within the provided stub.
     *
     * @param  array  $variables
     * @param  string  $value
     * @param  string  &$stub
     * @return 
     */
    protected function replaceInProvidedStub(array $variables, string $value, string &$stub) : void
    {
        $stub = str_replace($variables, $value, $stub);
    }

    /**
     * Get the camelcase version of the class from the namespace.
     *
     * @param  string  $value
     * @return string
     */
    public function getClassBasename(string $value) : string
    {
        return lcfirst(class_basename($value)) ?? '';
    }

    /**
     * Get the end of line formatting.
     *
     * @return string
     */
    protected function getEndOfLineFormatting() : string
    {
        return $this->endOfLine ?? $this->defaultEndOfLine;
    }

    /**
     * Remove the formatting from the end of the value.
     *
     * @param  string  &$value
     * @return void
     */
    public function removeEndOfLineFormatting(string &$value) : void
    {
        if ($this->hasEndOfLineFormatting($value)) {
            $value = substr($value, 0, -strlen($this->getEndOfLineFormatting()));
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
        $this->hasEndOfLineFormatting($value) ?: $value .= $this->getEndOfLineFormatting();
    }

    /**
     * Determine if the value need formatting removed from the end.
     *
     * @param  string  $value
     * @return boolean
     */
    protected function hasEndOfLineFormatting(string $value) : bool
    {
        return substr($value, -strlen($this->getEndOfLineFormatting())) === $this->getEndOfLineFormatting();
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $path
     * @return string
     */
    protected function resolveStubPath(string $path) : string
    {
        return File::exists($customPath = base_path(trim($path, '/')))
            ? $customPath
            : dirname(__DIR__) . '/Commands/' . $path;
    }

    /**
     * Check if the stub already contains the provided string.
     *
     * @param  string  $needle
     * @param  string|null  $stub = null
     * @return boolean
     */
    public function stubContains(string $needle, ?string $stub = null) : bool
    {
        if (is_null($stub)) {
            return str_contains($this->stub, $needle);
        }

        return str_contains($stub, $needle);
    }
}