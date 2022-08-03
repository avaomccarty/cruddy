<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns;

class ControllerColumn extends ColumnStub
{
    /**
     * The string needed between values.
     *
     * @var string
     */
    protected $spacer = ',';

    /**
     * Set the placeholder value map.
     *
     * @return self
     */
    protected function setPlaceholderValueMap() : self
    {
        $this->placeholderValueMap = [];

        return $this;
    }

    /**
     * Get the initial stub.
     *
     * @return string
     */
    protected function getInitialStub() : string
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