<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules;

use Cruddy\StubEditors\CollectionStub;
use Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\ForeignKey\ForeignKeyCollection;
use Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\RuleTrait;
use Illuminate\Support\Fluent;

class RuleCollection extends CollectionStub
{
    use RuleTrait;

    /**
     * The acceptable stubs.
     *
     * @var string[]
     */
    protected $acceptableStubs = [
        ColumnCollection::class,
        ForeignKeyCollection::class,
    ];

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
        ];

        return $this;
    }
}