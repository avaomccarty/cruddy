<?php

namespace Cruddy\Traits\Stubs;

trait ModelTrait
{
    /**
     * The acceptable model placeholders within a stub.
     *
     * @var array
     */
    protected $stubModelPlaceholders = [
        'DummyModelClass',
        '{{ model }}',
        '{{model}}'
    ];

    /**
     * The acceptable model variable placeholders within a stub.
     *
     * @var array
     */
    protected $stubModelVariablePlaceholders = [
        'DummyModelVariable',
        '{{ modelVariable }}',
        '{{modelVariable}}'
    ];

    /**
     * The acceptable full model class placeholders within a stub.
     *
     * @var array
     */
    protected $stubFullModelClassPlaceholders = [
        'DummyFullModelClass',
        '{{ namespacedModel }}',
        '{{namespacedModel}}'
    ];

    /**
     * The acceptable model name placeholders within a stub.
     *
     * @var array
     */
    protected $stubModelNamePlaceholders = [
        'DummyModelName',
        '{{ ModelName }}',
        '{{ModelName}}'
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
     * Replace the model placeholders within a stub.
     *
     * @param  string  $model
     * @param  string  &$stub
     * @return self
     */
    protected function replaceModelPlaceholders(string $model, string &$stub) : self
    {
        $stub = str_replace($this->stubModelPlaceholders, $this->getClassBasename($model), $stub);

        return $this;
    }

    /**
     * Replace the model variable placeholders within a stub.
     *
     * @param  string  $model
     * @param  string  &$stub
     * @return self
     */
    protected function replaceModelVariablePlaceholders(string $model, string &$stub) : self
    {
        $stub = str_replace($this->stubModelVariablePlaceholders, $this->getClassBasename($model), $stub);

        return $this;
    }

    /**
     * Replace the model placeholders within a stub.
     *
     * @param  string  $model
     * @param  string  &$stub
     * @return self
     */
    protected function replaceFullModelPlaceholders(string $model, string &$stub) : self
    {
        $stub = str_replace($this->stubFullModelClassPlaceholders, $model, $stub);

        return $this;
    }
}