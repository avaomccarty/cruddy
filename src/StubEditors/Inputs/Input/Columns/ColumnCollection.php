<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns;

use Cruddy\StubEditors\CollectionStub;
use Illuminate\Database\Schema\ColumnDefinition;

class ColumnCollection extends CollectionStub
{
    /**
     * The acceptable stubs.
     *
     * @var string[]
     */
    protected $acceptableStubs = [
        ColumnRuleCollector::class
    ];


    /**
     * The constructor method.
     *
     * @param  \Illuminate\Database\Schema\ColumnDefinition  $column
     * @param  array  $foreignKeys = []
     * @return void
     */
    public function __construct(protected ColumnDefinition $column, protected array $foreignKeys = [])
    {
        parent::__construct($this->stubs);
    }

    /**
     * Set the stub parameters.
     *
     * @param  string  $stubClass
     * @return self
     */
    protected function setStubParameters(string $stubClass) : self
    {
        $this->parameters = [
            $this->column,
            $this->foreignKeys,
        ];

        return $this;
    }

}