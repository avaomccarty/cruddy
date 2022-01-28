<?php

namespace Cruddy\StubEditors\Inputs;

use Cruddy\StubEditors\EmptyPlaceholderStub;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Fluent;
use Cruddy\StubEditors\Inputs\Input\InputStub;

abstract class InputsStub extends EmptyPlaceholderStub
{
    /**
     * The name of the resource.
     *
     * @var string
     */
    protected $nameOfResource = '';
    
    /**
     * The input stub editor.
     *
     * @var \Cruddy\StubEditors\Inputs\Input\InputStub|null
     */
    protected $inputStubEditor;
    
    /**
     * The view type.
     *
     * @var string
     */
    protected $viewType = '';

    /**
     * The constructor method.
     *
     * @param  array  $columns
     * @param  string  $fileType = ''
     * @param  array  $foreignKeys = []
     * @return void
     */
    public function __construct(protected array $columns, protected string $fileType = '', protected array $foreignKeys = [])
    {
        parent::__construct();
    }

    /**
     * Get the initial stub.
     *
     * @return string
     */
    protected function getInitialStub() : string
    {
        $this->addColumns();

        return $this->stub;
    }

    /**
     * Determine if the file should have a submit button.
     *
     * @return boolean
     */
    protected function shouldHaveSubmitButton() : bool
    {
        return $this->fileType === 'view' && in_array($this->viewType, [
            'create',
            'edit',
            'page',
        ]);
    }
        
    /**
     * Set the needs submit button.
     *
     * @return void
     */
    protected function setNeedsSubmitButton() : void
    {
       $this->needsSubmitButton = $this->shouldHaveSubmitButton(); 
    }

    /**
     * Set the view type.
     *
     * @param  string  $viewType
     * @return self
     */
    public function setViewType(string $viewType) : self
    {
        $this->viewType = $viewType;
    
        return $this;
    }

    /**
     * Set the column stub editor.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return self
     */
    protected function setInputStubEditor(Fluent $column) : self
    {
        $this->inputStubEditor = App::make(InputStub::class, [
            $column,
            $this->foreignKeys,
        ]);

        return $this;
    }

    /**
     * Set the name of the resource.
     *
     * @param  string  $nameOfResource
     * @return void
     */
    public function setNameOfResource(string $nameOfResource) : void
    {
        $this->nameOfResource = $nameOfResource;
    }

    /**
     * Add the columns to the input string.
     *
     * @return self
     */
    protected function addColumns() : self
    {
        foreach ($this->columns as $column) {
            $this->addColumn($column);
        }

        return $this;
    }

    /**
     * Add a column to the input string. 
     *
     * @param  \Illuminate\Support\Fluent|null  $column = null
     * @return void
     */
    protected function addColumn(?Fluent $column = null) : void
    {
        $this->setInputStubEditor($column);

        $this->addValue($this->inputStubEditor->getStub());
    }
}