<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns;

use Cruddy\StubEditors\Inputs\Input\InputStubEditor;
use Illuminate\Database\Schema\ColumnDefinition;

abstract class ColumnInputStubEditor extends InputStubEditor
{
    /**
     * The foreign keys.
     *
     * @var array
     */
    protected $foreignKeys = [];
    
    /**
     * The column.
     *
     * @var \Illuminate\Database\Schema\ColumnDefinition
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
     * @param  \Illuminate\Database\Schema\ColumnDefinition  $column
     * @param  string  &$stub = ''
     * @return void
     */
    public function __construct(ColumnDefinition $column, string &$stub = '')
    {
        parent::__construct($column, $stub);
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
     * Get the input as a string.
     *
     * @param  string  $type = 'index'
     * @param  string  $name = ''
     * @return string
     */
    public function getInputString() : string
    {
        return '';
    }

    /**
     * Get the column input as a string.
     *
     * @param  string  $type = 'index'
     * @param  string  $name = ''
     * @return string
     */
    public function getColumnInputString(string $type = 'index', string $name = '') : string
    {
        return $this->getInputString();
    }
}
