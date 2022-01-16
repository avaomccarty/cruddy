<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns;

use Cruddy\StubEditors\Inputs\Input\InputStubEditor;
use Illuminate\Database\Schema\ColumnDefinition;

abstract class ColumnInputStubEditor extends InputStubEditor
{
    /**
     * The constructor method.
     *
     * @param  \Illuminate\Database\Schema\ColumnDefinition  $column
     * @param  string  $stub = ''
     * @param  array  $foreignKeys = []
     * @return void
     */
    public function __construct(protected ColumnDefinition $column, protected string $stub = '', protected array $foreignKeys = [])
    {
        parent::__construct($column, $stub, $foreignKeys);
    }
}
