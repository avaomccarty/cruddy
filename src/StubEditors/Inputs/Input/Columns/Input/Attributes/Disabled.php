<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Input\Attributes;

class Disabled extends Attribute
{
    /**
     * Get the initial stub.
     *
     * @return string
     */
    protected function getInitialStub() : string
    {
        return 'disabled="disabled"';
    }

    /**
     * Determine if stub should be empty.
     *
     * @return boolean
     */
    protected function shouldHaveStub() : bool
    {
        return $this->type === 'show';
    }
}