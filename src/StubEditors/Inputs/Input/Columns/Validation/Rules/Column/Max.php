<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules;

use Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\Column\Column;
use Cruddy\Traits\MaxTrait;

class Max extends Column
{
    use MaxTrait;

    /**
     * Get the initial stub.
     *
     * @return string
     */
    protected function getInitialStub() : string
    {
        return 'max:' . $this->column->max;
    }
}