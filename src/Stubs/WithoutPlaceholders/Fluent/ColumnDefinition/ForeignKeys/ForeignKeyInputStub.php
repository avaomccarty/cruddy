<?php

namespace Cruddy\StubEditors\Inputs\Input\ForeignKeys;

use Cruddy\ForeignKeyDefinition;
use Cruddy\StubEditors\Inputs\Input\InputStub;

abstract class ForeignKeyInputStub extends InputStub
{
    /**
     * The constructor method.
     *
     * @param  \Cruddy\ForeignKeyDefinition  $column
     * @param  array  $foreignKeys = []
     * @return void
     */
    public function __construct(protected ForeignKeyDefinition $column, protected array $foreignKeys = [])
    {
        parent::__construct($column, $foreignKeys);
    }
}