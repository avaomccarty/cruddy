<?php

namespace Cruddy\StubEditors\Inputs\Input;

class ControllerStubInputEditor extends StubInputEditor
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