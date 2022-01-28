<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules;

use Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\Column\Column;

class Nullable extends Column
{
    /**
     * The initial stub.
     *
     * @var string
     */
    protected $initialStub = 'nullable';

    /**
     * Determine if stub should be empty.
     *
     * @return boolean
     */
    protected function shouldHaveStub() : bool
    {
        return isset($this->column->nullable) && $this->column->nullable;
    }
}