<?php

namespace Cruddy\Traits\Stubs;

trait RuleTrait
{
    /**
     * The acceptable rule placeholders within a stub.
     *
     * @var array
     */
    protected $stubRulePlaceholders = [
        'DummyRules',
        '{{ rules }}',
        '{{rules}}'
    ];
}