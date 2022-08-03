<?php

namespace Cruddy\Stubs\WithoutPlaceholders\Column;

use Cruddy\Stubs\CollectorStub;
use Illuminate\Database\Schema\ColumnDefinition;

class ColumnCollector extends CollectorStub
{
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
            $this->column
        ];
    }
}