<?php

namespace Cruddy\Stubs\Traits;

trait EmptyPlaceholderStubParametersTrait
{
    /**
     * Set the stub parameters.
     *
     * @param  string  $stubClass
     * @return self
     */
    protected function setStubParameters(string $stubClass) : self
    {
        return $this;
    }
}