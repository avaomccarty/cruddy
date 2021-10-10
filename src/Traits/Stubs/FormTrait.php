<?php

namespace Cruddy\Traits\Stubs;

trait FormTrait
{
    /**
     * The acceptable action placeholders within a stub.
     *
     * @var array
     */
    protected $stubActionPlaceholders = [
        'DummyAction',
        '{{ action }}',
        '{{action}}'
    ];

}