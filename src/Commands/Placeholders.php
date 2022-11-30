<?php

namespace Cruddy\Commands;

trait Placeholders
{
    protected $placeholders = [];
    
    /**
     * Get the placeholders.
     *
     * @return array
     */
    protected function getPlaceholders() : array
    {
        return (array)$this->placeholders;
    }

    /**
     * Set the placeholders for the command.
     *
     * @param  array  $placeholders
     * @return self
     */
    protected function setPlaceholders(array $placeholders) : self
    {
        $this->placeholders = $placeholders;
    
        return $this;
    }

    /**
     * The acceptable model placeholders within a stub.
     *
     * @var string[]
     */
    protected $modelPlaceholders = [
        'DummyModelClass',
        '{{ model }}',
        '{{model}}'
    ];

    /**
     * The acceptable model variable placeholders within a stub.
     *
     * @var string[]
     */
    protected $modelVariablePlaceholders = [
        'DummyModelVariable',
        '{{ modelVariable }}',
        '{{modelVariable}}'
    ];

    /**
     * The acceptable full model class placeholders within a stub.
     *
     * @var string[]
     */
    protected $fullModelClassPlaceholders = [
        'DummyFullModelClass',
        '{{ namespacedModel }}',
        '{{namespacedModel}}'
    ];


    /**
     * The acceptable resource placeholders within a stub.
     *
     * @var string[]
     */
    protected $resourcePlaceholders = [
        'DummyResource',
        '{{ resource }}',
        '{{resource}}'
    ];
}