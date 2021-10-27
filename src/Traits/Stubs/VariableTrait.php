<?php

namespace Cruddy\Traits\Stubs;

trait VariableTrait
{
    use StubTrait;

    /**
     * The acceptable value placeholders within a stub.
     *
     * @var array
     */
    protected $valuePlaceholders = [
        'DummyValue',
        '{{ value }}',
        '{{value}}'
    ];

    /**
     * The acceptable collection of variable placeholders within a stub.
     *
     * @var array
     */
    protected $variableCollectionPlaceholders = [
        'DummyVariableCollection',
        '{{ variableCollection }}',
        '{{variableCollection}}'
    ];

    /**
     * The acceptable variable placeholders within a stub.
     *
     * @var array
     */
    protected $variablePlaceholders = [
        'DummyVariable',
        '{{ variable }}',
        '{{variable}}'
    ];

    /*
     * The acceptable resource placeholders within a stub.
     *
     * @var array
     */
   protected $resourcePlaceholders = [
       'DummyResource',
       '{{ resource }}',
       '{{resource}}'
   ];
}