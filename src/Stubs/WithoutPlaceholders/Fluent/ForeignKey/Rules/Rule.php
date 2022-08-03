<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\ForeignKey;

class Rule extends ForeignKeyRule
{
    /**
     * Get the initial stub.
     *
     * @return string
     */
    protected function getInitialStub() : string
    {
        return 'exists:' . $this->column->on . ',' . $this->column->references;
    }

    /**
     * Determine if stub should be empty.
     *
     * @return boolean
     */
    protected function shouldHaveStub() : bool
    {
        return isset($this->column->on) && isset($this->column->references);
    }
}