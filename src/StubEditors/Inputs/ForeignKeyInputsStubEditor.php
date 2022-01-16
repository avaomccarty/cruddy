<?php

namespace Cruddy\StubEditors\Inputs;

use Cruddy\ForeignKeyDefinition;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Fluent;

class ForeignKeyInputsStubEditor extends InputsStubEditor
{
    /**
     * The validation for a foreign key.
     *
     * @var \Cruddy\ForeignKeyValidation\ForeignKeyValidation
     */
    protected $foreignKeyValidation;  
    
    /**
     * Get a validation rule string from a foreignKey.
     *
     * @param  \Cruddy\ForeignKeyDefinition  $foreignKey
     * @return string
     */
    protected function getForeignKeyValidation(ForeignKeyDefinition $foreignKey) : string
    {
        $this->setForeignKeyValidation($foreignKey);

        return $this->foreignKeyValidation->getForeignKeyValidation();
    }

    /**
     * Set the foreign key validation.
     *
     * @param  \Cruddy\ForeignKeyDefinition  $foreignKey
     * @return void
     */
    protected function setForeignKeyValidation(ForeignKeyDefinition $foreignKey) : void
    {
        $this->foreignKeyValidation = App::make(ForeignKeyValidation::class, [$foreignKey]);
    }

    /**
     * Add a column to the input string.
     *
     * @param  \Illuminate\Support\Fluent|null  $column = null
     * @return void
     */
    protected function addColumn(?Fluent $column = null) : void
    {
        if (is_null($column)) {
            // Todo: Handle null column here. Should probably just throw some custom exception.
        }

        $this->setStubInputEditor($column);

        $this->inputString .= $this->inputStubEditor->getInputString($this->inputStringType, $this->inputStringName);
    }
    
    /**
     * Set the column stub editor.
     *
     * @param  \Illuminate\Support\Fluent|null  $column = null
     * @return void
     */
    protected function setStubInputEditor(?Fluent $column = null) : void
    {
        $this->inputStubEditor = App::make(ForeignKeyInputStubEditor::class, [$column]);
    }
}
