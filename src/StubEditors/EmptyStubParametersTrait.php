<?php

namespace Cruddy\StubEditors;

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