<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Validation;

use Cruddy\StubEditors\CollectorStub;
use Cruddy\StubEditors\Inputs\Input\Columns\Validation\Rules\RuleCollection;
use Illuminate\Support\Fluent;

class ValidationCollector extends CollectorStub
{
    /**
     * The collection stub class.
     *
     * @var string
     */
    protected $collectionStubClass = RuleCollection::class;

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
}