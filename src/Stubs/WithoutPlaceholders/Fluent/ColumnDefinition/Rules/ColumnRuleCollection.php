<?php

namespace Cruddy\Stubs\WithoutPlaceholders\Column\Rules;

use Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\RuleCollection;
use Illuminate\Database\Schema\ColumnDefinition;

class ColumnRuleCollection extends RuleCollection
{
    /**
     * The acceptable stubs.
     *
     * @var string[]
     */
    protected $acceptableStubs = [
        Integer::class,
        Max::class,
        Min::class,
        Nullable::class,
        Unsigned::class,
    ];

    /**
     * The constructor method.
     *
     * @param  \Illuminate\Database\Schema\ColumnDefinition  $column
     * @return void
     */
    public function __construct(protected ColumnDefinition $column)
    {
        parent::__construct($column);
    }
}