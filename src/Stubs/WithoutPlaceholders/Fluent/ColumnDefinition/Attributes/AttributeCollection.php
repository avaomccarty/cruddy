<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Input;

use Cruddy\StubEditors\CollectionStub;
use Illuminate\Database\Schema\ColumnDefinition;

class AttributeCollection extends CollectionStub
{
    /**
     * The acceptable stubs.
     *
     * @var string[]
     */
    protected $acceptableStubs = [
        Checked::class,
        Disabled::class,
        Max::class,
        Min::class,
        Value::class,
    ];

    /**
     * The constructor method.
     *
     * @param  \Illuminate\Database\Schema\ColumnDefinition  $column
     * @return void
     */
    public function __construct(protected ColumnDefinition $column)
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
            $this->column
        ];

        return $this;
    }
}