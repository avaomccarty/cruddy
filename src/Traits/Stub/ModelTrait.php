<?php

namespace Cruddy\Traits\Stub;

trait ModelTrait
{
    /**
     * The acceptable stub dummy model strings.
     *
     * @var array
     */
    protected $stubVariableModels = [
        'DummyModelClass',
        '{{ model }}',
        '{{model}}'
    ];
}