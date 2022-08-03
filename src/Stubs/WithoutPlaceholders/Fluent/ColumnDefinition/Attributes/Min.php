<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Input\Attributes;

use Cruddy\Traits\MinTrait;

class Min extends Attribute
{
    use MinTrait;

    /**
     * Get the initial stub.
     *
     * @return string
     */
    protected function getInitialStub() : string
    {
        return 'min="' . $this->column->min . '"';
    }
}