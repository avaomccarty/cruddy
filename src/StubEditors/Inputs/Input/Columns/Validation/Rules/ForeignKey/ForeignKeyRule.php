<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\ForeignKey;

use Cruddy\ForeignKeyDefinition;
use Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\Rule;

abstract class ForeignKeyRule extends Rule
{
    /**
     * The constructor method.
     *
     * @param  \Cruddy\ForeignKeyDefinition  $column
     * @return void
     */
    public function __construct(protected ForeignKeyDefinition $column)
    {
        parent::__construct($column);
    }
}