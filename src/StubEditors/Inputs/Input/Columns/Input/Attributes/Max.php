<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Input\Attributes;

use Cruddy\Traits\MaxTrait;

class Max extends Attribute
{
    use MaxTrait;

    /**
     * Get the initial stub.
     *
     * @return string
     */
    protected function getInitialStub() : string
    {
        return 'max="' . $this->column->max . '"';
    }
}