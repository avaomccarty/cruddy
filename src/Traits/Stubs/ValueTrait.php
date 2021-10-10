<?php

namespace Cruddy\Traits\Stubs;

trait ValueTrait
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
}