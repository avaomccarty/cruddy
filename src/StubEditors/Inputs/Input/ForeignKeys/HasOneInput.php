<?php

namespace Cruddy\StubEditors\Inputs\Input\ForeignKeys;

class HasOneInput extends ForeignKeyInputStubEditor
{
    /**
     * Get the input as a string.
     *
     * @param  string  $type = 'index'
     * @param  string  $name = ''
     * @return string
     */
    public function getInputString(string $type = 'index', string $name = '') : string
    {
        return $this->column->name;
    }
}