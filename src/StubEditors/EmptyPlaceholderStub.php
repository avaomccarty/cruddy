<?php

namespace Cruddy\StubEditors;

abstract class EmptyPlaceholderStub extends Stub
{
    /**
     * Get the updated stub.
     *
     * @return string
     */
    public function getStub() : string
    {
        return $this->stub;
    }
}