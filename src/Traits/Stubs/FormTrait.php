<?php

namespace Cruddy\Traits\Stubs;


trait FormTrait
{
    use StubTrait;

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
}