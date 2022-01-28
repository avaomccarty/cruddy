<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\ForeignKey;

use Cruddy\Fluent\ForeignKeyTrait;
use Cruddy\ForeignKeyDefinition;
use Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\RuleCollection;

class ForeignKeyCollection extends RuleCollection
{
    use ForeignKeyTrait;

    /**
     * The acceptable stubs.
     *
     * @var string[]
     */
    protected $acceptableStubs = [
        ForeignKeyRule::class,
    ];

    /**
     * The constructor method.
     *
     * @param  \Cruddy\ForeignKeyDefinition  $column
     * @return void
     */
    public function __construct(protected ForeignKeyDefinition $column)
    {
        parent::__construct($column);
    }
}