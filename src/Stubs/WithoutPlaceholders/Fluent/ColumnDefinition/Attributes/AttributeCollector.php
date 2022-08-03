<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Input;

use Cruddy\Stubs\WithoutPlaceholders\Column\ColumnCollector;

class AttributeCollector extends ColumnCollector
{
    /**
     * The collection stub class.
     *
     * @var string
     */
    protected $collectionStubClass = AttributeCollection::class;
}