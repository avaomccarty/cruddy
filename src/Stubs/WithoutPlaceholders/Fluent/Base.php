<?php

namespace Cruddy\Stubs\WithoutPlaceholders\Fluent;

use Cruddy\Stubs\WithoutPlaceholders\StubWithoutPlaceholders;
use Illuminate\Support\Fluent;

abstract class Base extends StubWithoutPlaceholders
{
    /**
     * The constructor method.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return void
     */
    public function __construct(protected Fluent $column)
    {
        parent::__construct();
    }
}