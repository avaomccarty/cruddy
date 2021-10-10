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
     * Replace the model placeholders within a stub.
     *
     * @param  string  $model
     * @param  string  &$stub
     * @return self
     */
    public function replaceModelPlaceholder(string $model, string &$stub)
    {
        $stub = str_replace($this->stubModelPlaceholders, class_basename($model), $stub);

        return $this;
    }

    /**
     * Replace the model placeholders within a stub.
     *
     * @param  string  $model
     * @param  string  &$stub
     * @return self
     */
    public function replaceModelVariablePlaceholders(string $model, string &$stub)
    {
        str_replace($this->stubModelVariablePlaceholders, lcfirst(class_basename($model)), $stub);

        return $this;
    }

    /**
     * Replace the model placeholders within a stub.
     *
     * @param  string  $model
     * @param  string  &$stub
     * @return self
     */
    public function replaceFullModelPlaceholders(string $model, string &$stub)
    {
        str_replace($this->stubFullModelClassPlaceholders, $model, $stub);

        return $this;
    }
}