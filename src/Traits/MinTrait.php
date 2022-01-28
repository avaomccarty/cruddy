<?php

namespace Cruddy\Traits;

trait MinTrait
{
    /**
     * Determine if stub should be empty.
     *
     * @return boolean
     */
    protected function shouldHaveStub() : bool
    {
        return $this->isIntegerColumn() && is_numeric($this->column->min);
    }
}