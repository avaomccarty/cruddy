<?php

namespace Cruddy\StubEditors\Inputs\Input\ForeignKeys;

class HasOneInput extends ForeignKeyInputStub
{
    /**
     * Get the stub value.
     *
     * @return string
     */
    protected function getInitialStubValue() : string
    {
        return $this->column->name;
    }
}