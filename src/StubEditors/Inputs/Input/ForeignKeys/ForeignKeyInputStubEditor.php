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
     * @param  string  &$stub = ''
     * @return void
     */
    public function __construct(ForeignKeyDefinition $column, string &$stub = '')
    {
        parent::__construct($column, $stub);
    }
}