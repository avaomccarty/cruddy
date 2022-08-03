<?php

namespace Cruddy\Stubs\WithoutPlaceholders;

use Cruddy\Stubs\Stub;

abstract class StubWithoutPlaceholders extends Stub
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