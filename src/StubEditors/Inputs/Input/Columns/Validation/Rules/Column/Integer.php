<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules;

use Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\Column\Column;

class Integer extends Column
{
    /**
     * The initial stub.
     *
     * @var string
     */
    protected $initialStub = 'integer';

    /**
     * Determine if stub should be empty.
     *
     * @return boolean
     */
    protected function shouldHaveStub() : bool
    {
        return $this->isIntegerColumn();
    }
}