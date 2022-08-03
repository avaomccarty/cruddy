<?php

namespace Cruddy\Stubs\WithoutPlaceholders\Fluent\ColumnDefinition\Attributes;

use Cruddy\Stubs\WithPlaceholders\StubWithoutPlaceholders;
use Illuminate\Database\Schema\ColumnDefinition;

abstract class Attribute extends StubWithoutPlaceholders
{
    /**
     * The string needed between values.
     *
     * @var string
     */
    protected $spacer = ' ';

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