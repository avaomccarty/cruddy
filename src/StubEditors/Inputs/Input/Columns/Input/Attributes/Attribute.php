<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Input\Attributes;

use Cruddy\StubEditors\EmptyPlaceholderStub;
use Cruddy\StubEditors\Inputs\Input\Columns\Input\AttributeTrait;
use Illuminate\Database\Schema\ColumnDefinition;

abstract class Attribute extends EmptyPlaceholderStub
{
    use AttributeTrait;

    /**
     * The constructor method.
     *
     * @param  \Illuminate\Database\Schema\ColumnDefinition  $column
     * @param  string  $type = 'index'
     * @return void
     */
    public function __construct(protected ColumnDefinition $column, protected string $type = 'index')
    {
        parent::__construct();
    }
}