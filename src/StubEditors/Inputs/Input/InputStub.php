<?php

namespace Cruddy\StubEditors\Inputs\Input;

use Cruddy\Exceptions\UnknownInputType;
use Cruddy\ForeignKeyDefinition;
use Cruddy\StubEditors\HasPlaceholderStub;
use Illuminate\Support\Fluent;

abstract class InputStub extends HasPlaceholderStub
{
    /**
     * The foreign keys belonging to the column.
     *
     * @var \Cruddy\ForeignKeyDefinition[]
     */
    protected $columnForeignKeys = [];

    /**
     * The constructor method.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @param  array  $foreignKeys = []
     * @return void
     */
    public function __construct(protected Fluent $column, protected array $foreignKeys = [])
    {
        parent::__construct();

        $this->setColumnForeignKeys();
    }

    /**
     * Set the foreign keys for the column.
     *
     * @return self
     */
    protected function setColumnForeignKeys() : self
    {
        $this->columnForeignKeys = $this->getForeignKeysForColumn();
    
        return $this;
    }

    /**
     * Get the foreign keys for a column.
     *
     * @return array
     */
    protected function getForeignKeysForColumn() : array
    {
        return array_filter($this->foreignKeys, function ($foreignKey) {
            return $this->isForeignKeyForColumn($foreignKey);
        });
    }

    /**
     * Determine if the foreign key is for the column.
     *
     * @param  \Cruddy\ForeignKeyDefinition  $foreignKey
     * @return boolean
     */
    protected function isForeignKeyForColumn(ForeignKeyDefinition $foreignKey) : bool
    {
        return in_array($this->column->name, $foreignKey->columns);
    }

    /**
     * Get the stub input file.
     *
     * @return string
     */
    protected function getStubLocation() : string
    {
        return $this->resolveStubPath($this->getInputStubLocation());
    }

    /**
     * Get the stub file location.
     *
     * @return string
     */
    protected function getInputStubLocation() : string
    { 
        return $this->getStubsLocation() . '/views/' . $this->getFrontendScaffoldingName()  . '/inputs/' . $this->getInputType() . '.stub';
    }

    /**
     * Get the input type.
     *
     * @return string
     *
     * @throws \Cruddy\Exceptions\UnknownInputType
     */
    protected function getInputType() : string
    {
        return $this->hasValidColumnType() ? $this->getDefaultForInputType($this->getColumnType()) : throw new UnknownInputType();
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
        return $this->column['type'];
    }
}