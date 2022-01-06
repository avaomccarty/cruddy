<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns;

use Cruddy\StubEditors\Inputs\Input\InputStubEditor;

class ControllerStubInputEditor extends InputStubEditor
{
    /**
     * Get the input as a string.
     *
     * @return string
     */
    public function getInputString() : string
    {
        $this->inputString = $this->isIdColumn() ? '' : $this->getColumnString();

        return $this->inputString;
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
        return "'" . $this->column->name . "'" . ' => $request->' . $this->column->name . "," . $this->endOfLine;
    }
}