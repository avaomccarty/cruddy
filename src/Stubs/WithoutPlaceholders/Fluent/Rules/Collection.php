<?php

namespace Cruddy\Stubs\WithoutPlaceholders\Rules;

use Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\RuleTrait;
use Cruddy\Stubs\CollectionStub;
use Cruddy\Stubs\WithoutPlaceholders\Column\Rules\Collection as ColumnRuleCollection;
use Cruddy\Stubs\WithoutPlaceholders\ForeignKey\Rules\Collection as ForeignKeyRuleCollection;
use Illuminate\Support\Fluent;

class Collection extends CollectionStub
{
    use RuleTrait;

    /**
     * The acceptable stubs.
     *
     * @var string[]
     */
    protected $acceptableStubs = [
        ColumnRuleCollection::class,
        ForeignKeyRuleCollection::class,
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