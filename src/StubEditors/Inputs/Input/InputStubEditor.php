<?php

namespace Cruddy\StubEditors\Inputs\Input;

use Cruddy\StubEditors\StubEditor;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Fluent;

abstract class InputStubEditor extends StubEditor
{
    /**
     * The input as a string.
     *
     * @var string
     */
    protected $inputString = '';
    
    /**
     * The column.
     *
     * @var \Illuminate\Support\Fluent
     */
    protected $column;

    /**
     * The constructor method.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @param  string  &$stub = ''
     * @return void
     */
    public function __construct(Fluent $column, string &$stub = '')
    {
        $this->column = $column;
        $this->stub = $stub ?? $this->getStubFile();
    }

    /**
     * Get the input as a string.
     *
     * @return string
     */
    abstract public function getInputString() : string;

    /**
     * Get the stub.
     *
     * @return string
     */
    public function getStubFile() : string
    {
        return File::get($this->getInputStub());
    }

    /**
     * Get the stub input file.
     *
     * @return string
     */
    protected function getInputStub() : string
    {
        return $this->resolveStubPath($this->getInputStubLocation());
    }

    /**
     * Get the input stub file locaiton.
     *
     * @return string
     */
    protected function getInputStubLocation() : string
    { 
        return $this->getStubsLocation() . '/views/' . $this->getFrontendScaffoldingName()  . '/inputs/' . $this->getInputType() . '.stub';
    }

    /**
     * Get the input.
     *
     * @return string
     */
    protected function getInputType() : string
    {
        if ($this->hasValidColumnType()) {
            return $this->getDefaultForInputType($this->getColumnType());
        }

        return $this->getDefaultInput();
    }

    /**
     * Get the default input type.
     *
     * @return string
     */
    protected function getDefaultInput() : string
    {
        return 'submit';
    }

    /**
     * Determine if the column type is valid.
     *
     * @return boolean
     */
    protected function hasValidColumnType() : bool
    {
        return array_key_exists($this->getColumnType(), $this->getInputDefaults());
    }

    /**
     * Get the column type.
     *
     * @return string
     */
    protected function getColumnType() : string
    {
        return $this->column['type'] ?? $this->getDefaultInput();
    }
}