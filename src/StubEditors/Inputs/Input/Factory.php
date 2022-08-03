<?php

namespace Cruddy\StubEditors\Inputs\Input;

use Cruddy\Exceptions\StubEditors\Inputs\UnknownInputStubEditor;
use Cruddy\Factory as BaseFactory;
use Cruddy\Fluent\FluentInteractor;
use Cruddy\StubEditors\Inputs\Input\Columns\Factory as ColumnFactory;
use Cruddy\StubEditors\Inputs\Input\ForeignKeys\ForeignKeyInputStubFactory;
use Illuminate\Support\Fluent;

class Factory extends BaseFactory
{
    /**
     * The constructor method.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @param  string  $inputStubEditor = 'controller'
     * @param  array  $foreignKeys = []
     * @return void
     */
    public function __construct(protected Fluent $column, protected string $inputStubEditor = 'controller', protected array $foreignKeys = [])
    {
        parent::__construct();
    }

    /**
     * Set the parameters.
     *
     * @return void
     */
    protected function setParameters() : void
    {
        $this->parameters = [
            $this->column,
        ];

        if (FluentInteractor::isAColumnDefinition($this->column)) {
            $this->parameters[] = $this->inputStubEditor;
            $this->parameters[] = $this->foreignKeys;
        }
    }

    /**
     * Get the correct StubEditor
     *
     * @return \Cruddy\StubEditors\Inputs\Input\InputStub
     *
     * @throws \Cruddy\Exceptions\StubEditors\Inputs\UnknownInputStubEditor
     */
    public function get() : InputStub
    {
        switch ($this->column) {
            case FluentInteractor::isAColumnDefinition($this->column):
                return ($this->makeClass(ColumnFactory::class))
                    ->get();
            case FluentInteractor::isAForeignKeyDefinition($this->column):
                return ($this->makeClass(ForeignKeyInputStubFactory::class))
                    ->get();
            default:
                throw new UnknownInputStubEditor();
        }
    }
}