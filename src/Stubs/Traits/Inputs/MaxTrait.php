<?php

namespace Cruddy\Stubs\Traits\Inputs;

trait MaxTrait
{
    /**
     * Determine if stub should be empty.
     *
     * @return boolean
     */
    protected function shouldHaveStub() : bool
    {
        return $this->isIntegerColumn() && is_numeric($this->column->max);
    }
}