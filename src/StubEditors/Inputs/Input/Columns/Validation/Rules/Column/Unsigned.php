<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules;

use Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\Column\Column;

class Unsigned extends Column
{
    /**
     * The initial stub.
     *
     * @var string
     */
    protected $initialStub = 'unsigned';

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