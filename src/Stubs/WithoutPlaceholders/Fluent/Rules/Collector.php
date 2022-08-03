<?php

namespace Cruddy\Stubs\WithoutPlaceholders\Rules;

use Cruddy\Fluent\FluentInteractor;
use Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\Column\ColumnCollection;
use Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\ForeignKey\ForeignKeyCollection;
use Cruddy\Stubs\CollectorTrait;
use Illuminate\Support\Fluent;

class Collector extends Base
{
    use CollectorTrait;

    /**
     * The collection stub class.
     *
     * @var string
     */
    protected $collectionStubClass = ColumnCollection::class;

    /**
     * The constructor method.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return void
     */
    public function __construct(protected Fluent $column)
    {
        parent::__construct($column);

        $this->setCollectionStubClass();
    }

    /**
     * Get the stub collection parameters. 
     *
     * @return array
     */
    protected function getParameters() : array
    {
        return [
            $this->column,
        ];
    }

    /**
     * Set the collection stub class based on the column.
     *
     * @return self
     */
    protected function setCollectionStubClass() : self
    {
        if (FluentInteractor::isAColumnDefinition($this->column)) {
            $this->collectionStubClass = ColumnCollection::class;
        }

        if (FluentInteractor::isAForeignKeyDefinition($this->column)) {
            $this->collectionStubClass = ForeignKeyCollection::class;
        }

        return $this;
    }
}