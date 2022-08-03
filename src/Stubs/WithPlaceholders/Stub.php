<?php

namespace Cruddy\Stubs\WithPlaceholders;

use Cruddy\Stubs\Stub as BaseStub;

abstract class Stub extends BaseStub
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