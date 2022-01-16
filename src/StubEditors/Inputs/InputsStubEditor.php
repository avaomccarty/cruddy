<?php

namespace Cruddy\StubEditors\Inputs;

use Illuminate\Support\Fluent;

abstract class InputsStubEditor
{
    /**
     * The name of the resource.
     *
     * @var string
     */
    protected $nameOfResource = '';

    /**
     * The constructor method.
     *
     * @param  array  $columns
     * @param  string  $fileType = ''
     * @param  string  $stub = ''
     * @param  array  $foreignKeys = []
     * @return void
     */
    public function __construct(protected array $columns, protected string $fileType = '', protected string $stub = '', protected array $foreignKeys = [])
    {
        // Todo: Add logic here.
    }

    /**
     * Set the name of the resource.
     *
     * @param  string  $name
     * @return void
     */
    public function setNameOfResource(string $name) : void
    {
        $this->nameOfResource = $name;
    }

    /**
     * Get the name of the resource.
     *
     * @return string
     */
    protected function getNameOfResource() : string
    {
        return $this->nameOfResource;
    }

    /**
     * Add a column to the input string.
     *
     * @param  \Illuminate\Support\Fluent|null  $column = null
     * @return void
     */
    abstract protected function addColumn(?Fluent $column = null) : void;

    /**
     * Set the column stub editor.
     *
     * @param  \Illuminate\Support\Fluent|null  $column = null
     * @return void
     */
    abstract protected function setStubInputEditor(?Fluent $column = null) : void;
}