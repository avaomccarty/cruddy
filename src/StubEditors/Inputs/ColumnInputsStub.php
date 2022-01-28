<?php

namespace Cruddy\StubEditors\Inputs;

use Illuminate\Database\Schema\ColumnDefinition;

class ColumnInputsStub extends InputsStub
{
    /**
     * Determine if a submit input is needed.
     *
     * @var boolean
     */
    protected $needsSubmitButton = false;

    /**
     * Add a submit button to the input string, if it's needed.
     *
     * @return void
     */
    protected function addSubmitButtonIfNeeded() : void
    {
        if ($this->shouldHaveSubmitButton()) {
            $this->addSubmitColumn();
        }
    }

    /**
     * Add a submit column.
     *
     * @return void
     */
    protected function addSubmitColumn() : void
    {
        $this->addColumn($this->getSubmitColumn());
    }

    /**
     * Get a submit column.
     *
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    protected function getSubmitColumn() : ColumnDefinition
    {
        return new ColumnDefinition([
            'type' => 'submit',
            'name' => 'submit',
        ]);
    }

    /**
     * Add the columns to the input string.
     *
     * @return self
     */
    protected function addColumns() : self
    {
        parent::addColumns();

        $this->addSubmitButtonIfNeeded();

        return $this;
    }
}