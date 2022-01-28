<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns;

use Cruddy\StubEditors\Inputs\Input\InputStub;
use Illuminate\Database\Schema\ColumnDefinition;

abstract class ColumnStub extends InputStub
{
    /**
     * The constructor method.
     *
     * @param  \Illuminate\Database\Schema\ColumnDefinition  $column
     * @param  array  $foreignKeys = []
     * @return void
     */
    public function __construct(protected ColumnDefinition $column, protected array $foreignKeys = [])
    {
        parent::__construct($column, $foreignKeys);
    }

    /**
     * Get the initial stub.
     *
     * @return string
     */
    abstract protected function getInitialStub() : string;
}
