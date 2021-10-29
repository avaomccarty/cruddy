<?php

namespace Cruddy\Traits\Stubs;

use Cruddy\Traits\ConfigTrait;
use Illuminate\Support\Facades\File;

trait StubTrait
{
    use ConfigTrait;

    /**
     * The styling for the end of the Vue data.
     *
     * @var string
     */
    protected $endOfDataLine = "\n\t\t\t";

    /**
     * The styling for the end of the Vue post data.
     *
     * @var string
     */
    protected $endOfPostDataLine = "\n\t\t\t\t";

    /**
     * The default end of line formatting.
     *
     * @var string
     */
    protected $defaultEndOfLine = "\n\t\t";

    /**
     * The acceptable Vue data placeholders within a stub.
     *
     * @var array
     */
    protected $vueDataPlaceholders = [
        'DummyVueData',
        '{{ vueData }}',
        '{{vueData}}'
    ];

    /**
     * The acceptable Vue post data placeholders within a stub.
     *
     * @var array
     */
    protected $vuePostDataPlaceholders = [
        'DummyVuePostData',
        '{{ vuePostData }}',
        '{{vuePostData}}'
    ];

    /**
     * The acceptable Vue component placeholders within a stub.
     *
     * @var array
     */
    protected $vueComponentPlaceholders = [
        'DummyComponentName',
        '{{ componentName }}',
        '{{componentName}}'
    ];

    /**
     * The acceptable Vue props placeholders within a stub.
     *
     * @var array
     */
    protected $vuePropsPlaceholders = [
        'DummyProps',
        '{{ props }}',
        '{{props}}'
    ];

    /**
     * The acceptable component name placeholders within a stub.
     *
     * @var array
     */
    protected $componentNamePlaceholders = [
        'DummyComponentName',
        '{{ componentName }}',
        '{{componentName}}'
    ];

    /**
     * The acceptable action placeholders within a stub.
     *
     * @var array
     */
    protected $actionPlaceholders = [
        'DummyAction',
        '{{ action }}',
        '{{action}}'
    ];
        
    /**
     * The acceptable edit URL placeholders within a stub.
     *
     * @var array
     */
    protected $editUrlPlaceholders = [
        'DummyEditUrl',
        '{{ editUrl }}',
        '{{editUrl}}'
    ];

    /**
     * The acceptable cancel URL placeholders within a stub.
     *
     * @var array
     */
    protected $cancelUrlPlaceholders = [
        'DummyCancelUrl',
        '{{ cancelUrl }}',
        '{{cancelUrl}}'
    ];


    /**
     * The acceptable model placeholders within a stub.
     *
     * @var array
     */
    protected $modelPlaceholders = [
        'DummyModelClass',
        '{{ model }}',
        '{{model}}'
    ];

    /**
     * The acceptable model variable placeholders within a stub.
     *
     * @var array
     */
    protected $modelVariablePlaceholders = [
        'DummyModelVariable',
        '{{ modelVariable }}',
        '{{modelVariable}}'
    ];

    /**
     * The acceptable full model class placeholders within a stub.
     *
     * @var array
     */
    protected $fullModelClassPlaceholders = [
        'DummyFullModelClass',
        '{{ namespacedModel }}',
        '{{namespacedModel}}'
    ];

    /**
     * The acceptable model name placeholders within a stub.
     *
     * @var array
     */
    protected $modelNamePlaceholders = [
        'DummyModelName',
        '{{ ModelName }}',
        '{{ModelName}}'
    ];

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
     * The acceptable resource placeholders within a stub.
     *
     * @var array
     */
    protected $resourcePlaceholders = [
        'DummyResource',
        '{{ resource }}',
        '{{resource}}'
    ];

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

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub) : string
    {
        return File::exists($customPath = base_path(trim($stub, '/')))
            ? $customPath
            : dirname(dirname(__DIR__)) . '/Commands/' . $stub;
    }
}