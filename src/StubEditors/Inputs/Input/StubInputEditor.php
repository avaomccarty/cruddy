<?php

namespace Cruddy\StubEditors\Inputs\Input;

use Cruddy\StubEditors\StubEditor;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\File;

abstract class StubInputEditor extends StubEditor
{
    /**
     * The input as a string.
     *
     * @var string
     */
    protected $inputString = '';
        
    /**
     * The foreign keys.
     *
     * @var array
     */
    protected $foreignKeys = [];
    
    /**
     * The column.
     *
     * @var \Illuminate\Database\Schema\ColumnDefinition|null
     */
    protected $column;

    /**
     * The stub.
     *
     * @var string
     */
    protected $stub = '';

    /**
     * The constructor method.
     *
     * @param  \Illuminate\Database\Schema\ColumnDefinition|null  $column = null
     * @param  string  &$stub = ''
     * @return void
     */
    public function __construct(?ColumnDefinition $column = null, string &$stub = '')
    {
        $this->column = $column;
        $this->stub = $stub ?? $this->getStubFile();
    }

    /**
     * Get the input as a string.
     *
     * @param  string  $type = 'index'
     * @param  string  $name = ''
     * @return string
     */
    abstract public function getInputString(string $type = 'index', string $name = '') : string;

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
     * Set the foreign keys.
     *
     * @param  array  $foreignKeys = []
     * @return self
     */
    public function setForeignKeys(array $foreignKeys = []) : self
    {
        $this->foreignKeys = $foreignKeys;

        return $this;
    }

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