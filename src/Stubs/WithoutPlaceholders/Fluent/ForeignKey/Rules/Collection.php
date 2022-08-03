<?php

namespace Cruddy\Stubs\WithoutPlaceholders\ForeignKey\Rules;

use Cruddy\ForeignKeyDefinition;
use Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\RuleCollection;

class Collection extends RuleCollection
{
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