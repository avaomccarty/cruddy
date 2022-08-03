<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns;

use Cruddy\StubEditors\CollectorStub;
use Illuminate\Support\Fluent;

class ColumnCollector extends CollectorStub
{
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
     * @param  array  $foreignKeys
     * @return void
     */
    public function __construct(protected Fluent $column, protected array $foreignKeys)
    {
        parent::__construct();
    }

    /**
     * Get the collection stub parameters.
     *
     * @return array
     */
    protected function getParameters() : array
    {
        return [
            $this->column,
            $this->foreignKeys,
        ];
    }
}