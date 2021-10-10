<?php

namespace Cruddy\Traits\Stubs;

trait UrlTrait
{
    /**
     * The acceptable edit URL placeholders within a stub.
     *
     * @var array
     */
    protected $stubEditUrlPlaceholders = [
        'DummyEditUrl',
        '{{ editUrl }}',
        '{{editUrl}}'
    ];

    /**
     * The acceptable cancel URL placeholders within a stub.
     *
     * @var array
     */
    protected $stubCancelUrlPlaceholders = [
        'DummyCancelUrl',
        '{{ cancelUrl }}',
        '{{cancelUrl}}'
    ];

}