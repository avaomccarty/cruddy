<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\Column;

use Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\Rule;
use Illuminate\Database\Schema\ColumnDefinition;

abstract class Column extends Rule
{
    use ColumnTrait;

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