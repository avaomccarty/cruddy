<?php

namespace Cruddy\StubEditors\Inputs\Input\ForeignKeys;

use Cruddy\ForeignKeyDefinition;
use Cruddy\StubEditors\Inputs\Input\InputStubEditor;

abstract class ForeignKeyInputStubEditor extends InputStubEditor
{
    /**
     * The constructor method.
     *
     * @param  \Cruddy\ForeignKeyDefinition  $column
     * @param  string  $stub = ''
     * @param  array  $foreignKeys = []
     * @return void
     */
    public function __construct(protected ForeignKeyDefinition $column, protected string $stub = '', protected array $foreignKeys = [])
    {
        parent::__construct($column, $stub, $foreignKeys);
    }
}