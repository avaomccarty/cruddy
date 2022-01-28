<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Input;

use Cruddy\StubEditors\CollectionStub;

class InputCollection extends CollectionStub
{
    use AttributeTrait;

    /**
     * The acceptable stubs.
     *
     * @var string[]
     */
    protected $acceptableStubs = [
        Checked::class,
        Disabled::class,
        Max::class,
        Min::class,
        Value::class,
    ];
}