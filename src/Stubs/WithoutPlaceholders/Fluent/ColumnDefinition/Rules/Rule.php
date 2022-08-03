<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\Column;

use Illuminate\Database\Schema\ColumnDefinition;

abstract class Rule extends BaseRule
{
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