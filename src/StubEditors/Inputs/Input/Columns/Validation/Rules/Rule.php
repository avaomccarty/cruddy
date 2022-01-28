<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules;

use Cruddy\StubEditors\EmptyPlaceholderStub;
use Illuminate\Support\Fluent;

abstract class Rule extends EmptyPlaceholderStub
{
    use RuleTrait;

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