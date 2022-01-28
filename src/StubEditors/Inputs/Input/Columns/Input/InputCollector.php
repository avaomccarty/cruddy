<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Input;

use Cruddy\StubEditors\CollectorStub;
use Cruddy\StubEditors\Inputs\Input\Columns\Input\InputCollection;
use Illuminate\Database\Schema\ColumnDefinition;

class InputCollector extends CollectorStub
{
    /**
     * The collection stub class.
     *
     * @var string
     */
    protected $collectionStubClass = InputCollection::class;

    /**
     * The constructor method.
     *
     * @param  \Illuminate\Database\Schema\ColumnDefinition  $column
     * @param  array  $foreignKeys = []
     * @return void
     */
    public function __construct(protected ColumnDefinition $column, protected array $foreignKeys = [])
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
            $this->type
        ];
    }
}