<?php

namespace Cruddy\Traits\Stubs;

trait ModelTrait
{
    use StubTrait;

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
     * Get the camelcase version of the class from the namespace.
     *
     * @param  string  $value
     * @return string
     */
    public function getClassBasename(string $value) : string
    {
        return lcfirst(class_basename($value)) ?? '';
    }
}