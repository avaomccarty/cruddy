<?php

namespace Cruddy\Traits;

trait PlaceholdersTrait
{
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

    /**
     * The accptable use statement placeholders.
     *
     * @var string[]
     */
    protected $useStatementPlaceholders = [
        'DummyUseStatement',
        '{{ useStatement }}',
        '{{useStatement}}',
    ];

    /**
     * The acceptable model placeholders within a stub.
     *
     * @var string[]
     */
    protected $modelRelationshipPlaceholders = [
        'DummyRelationships',
        '{{ relationships }}',
        '{{relationships}}'
    ];
}