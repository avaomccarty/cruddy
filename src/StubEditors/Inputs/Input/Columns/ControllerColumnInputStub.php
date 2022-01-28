<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns;

class ControllerColumnInputStub extends ColumnStub
{
    /**
     * The string needed between values.
     *
     * @var string
     */
    protected $spacer = ',';

    /**
     * Get the value for the rule.
     *
     * @return string
     */
    protected function getInitialStubValue() : string
    {
        return $this->isIdColumn() ? '' : $this->getColumnString();
    }

    /**
     * Determine if the input is for an ID.
     *
     * @return boolean
     */
    protected function isIdColumn() : bool
    {
        return $this->column->name === 'id';
    }

    /**
     * Get a column as a string.
     *
     * @return string
     */
    protected function getColumnString() : string
    {
        return "'" . $this->column->name . "'" . ' => $request->' . $this->column->name;
    }
}