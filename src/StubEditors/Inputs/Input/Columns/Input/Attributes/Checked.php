<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Input\Attributes;

class Checked extends Attribute
{
    /**
     * Get the initial stub.
     *
     * @return string
     */
    protected function getInitialStub() : string
    {
        return 'checked="checked"';
    }

    /**
     * Determine if stub should be empty.
     *
     * @return boolean
     */
    protected function shouldHaveStub() : bool
    {
        return $this->isBooleanColumn() && $this->column->default == 1;
    }
        
    /**
     * Determine if the column is a boolean column.
     *
     * @return boolean
     */
    protected function isBooleanColumn() : bool
    {
        return $this->column->type === 'boolean' || $this->column->type === 'tinyInteger';
    }
}