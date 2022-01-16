<?php

namespace Cruddy\StubEditors\Inputs;

use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Fluent;

class ColumnInputsStubEditor extends InputsStubEditor
{
    /**
     * Determine if a submit input is needed.
     *
     * @var boolean
     */
    protected $needsSubmitButton = false;

    /**
     * The input string name.
     *
     * @var string
     */
    protected $inputStringName = '';

    /**
     * The input string type.
     *
     * @var string
     */
    protected $inputStringType = '';

    /**
     * Get the inputs as a string.
     *
     * @param  string  $type = 'index'
     * @param  string  $name = ''
     * @return string
     */
    public function getInputString(string $type = 'index', string $name = '') : string
    {
        $this->inputStringName = $name;
        $this->inputStringType = $type;

        $this->removeFormatting();

        return $this->inputString;
    }

    /**
     * Remove the end of the line formatting.
     *
     * @return void
     */
    protected function removeFormatting() : void
    {
        if (!is_a($this->columnInputStubEditor, ColumnInputStubEditor::class)) {
            $this->columnInputStubEditor = $this->setStubInputEditor($this->getDefaultColumnDefinition());
        }

        $this->columnInputStubEditor->removeEndOfLineFormatting($this->inputString);
    }

    /**
     * Get the default column definition.
     *
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    protected function getDefaultColumnDefinition() : ColumnDefinition
    {
        return $this->columns[0] ?? $this->getEmptyColumnDefinition();
    }

    /**
     * Get an empty column definition.
     *
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    protected function getEmptyColumnDefinition() : ColumnDefinition
    {
        return new ColumnDefinition();
    }

    /**
     * Set the column stub editor.
     *
     * @param  \Illuminate\Support\Fluent|null  $column = null
     * @return void
     */
    protected function setStubInputEditor(?Fluent $column = null) : void
    {
        $this->inputStubEditor = App::make(InputStubEditor::class, [
            $column,
            $this->fileType,
            $this->stub,
            $this->foreignKeys,
        ]);
    }

    /**
     * Get the foreign keys.
     *
     * @return array
     */
    protected function getForeignKeys() : array
    {
        return (array)$this->foreignKeys;
    }

    /**
     * Set the foreign keys.
     *
     * @param  array  $foreignKeys
     * @return self
     */
    public function setForeignKeys(array $foreignKeys) : self
    {
        $this->foreignKeys = $foreignKeys;
    
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
        $this->setStubInputEditor($column);
        $this->inputStubEditor->setForeignKeys($this->getForeignKeys());

        $this->inputString .= $this->inputStubEditor->getColumnInputString($this->inputStringType, $this->inputStringName);
    }

    /**
     * Add a submit button to the input string, if it's needed.
     *
     * @return void
     */
    protected function addSubmitButtonIfNeeded() : void
    {
        if ($this->shouldHaveSubmitButton()) {
            $this->addColumn();
        }
    }

    /**
     * Determine if the file should have a submit button.
     *
     * @return boolean
     */
    protected function shouldHaveSubmitButton() : bool
    {
        return $this->fileType === 'view' && in_array($this->inputStringType, [
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
}