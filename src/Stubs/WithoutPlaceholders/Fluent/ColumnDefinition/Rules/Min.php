<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules;

use Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\Column\Column;
use Cruddy\Traits\MinTrait;

class Min extends Column
{
    use MinTrait;

    /**
     * Get the initial stub.
     *
     * @return string
     */
    protected function getInitialStub() : string
    {
        return 'min:' . $this->column->min;
    }
}