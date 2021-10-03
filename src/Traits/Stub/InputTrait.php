<?php

namespace Cruddy\Traits\Stub;

trait InputTrait
{
    /**
     * The acceptable stub dummy inputs.
     *
     * @var array
     */
    protected $stubVariableInputs = [
        'DummyInputs',
        '{{ inputs }}',
        '{{inputs}}'
    ];
}