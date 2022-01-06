<?php

namespace Cruddy\StubEditors\Inputs\Input;

use Cruddy\Exceptions\StubEditors\Inputs\UnknownInputStubEditor;
use Cruddy\Factory;
use Cruddy\FluentInteractor;
use Cruddy\StubEditors\Inputs\Input\Columns\ColumnInputStubEditorFactory;
use Cruddy\StubEditors\Inputs\Input\ForeignKeys\ForeignKeyInputStubEditorFactory;
use Illuminate\Support\Fluent;

class InputStubEditorFactory extends Factory
{
    /**
     * The constructor method.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return void
     */
    public function __construct(protected Fluent $column, protected string $inputStubEditor = 'controller', protected string &$stub = '')
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
            array_push($this->parameters,
                $this->inputStubEditor,
                $this->stub,
            );
        }
    }
    /**
     * Get the correct StubEditor
     *
     * @return \Cruddy\StubEditors\Inputs\Input\InputStubEditor
     *
     * @throws \Cruddy\Exceptions\StubEditors\Inputs\UnknownInputStubEditor
     */
    public function get() : InputStubEditor
    {
        switch ($this->column) {
            case FluentInteractor::isAColumnDefinition($this->column):
                return ($this->makeClass(ColumnInputStubEditorFactory::class))
                    ->get();
            case FluentInteractor::isAForeignKey($this->column):
                return ($this->makeClass(ForeignKeyInputStubEditorFactory::class))
                    ->get();
            default:
                throw new UnknownInputStubEditor();
        }
    }


}