<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Input\Attributes;

class Value extends Attribute
{
    /**
     * The name of the resource.
     *
     * @var string
     */
    protected $nameOfResource = '';

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
     * Get the initial stub.
     *
     * @return string
     */
    protected function getInitialStub() : string
    {
        return 'value="' . $this->getInputValue() . '"';
    }

    /**
     * Determine if stub should be empty.
     *
     * @return boolean
     */
    protected function shouldHaveStub() : bool
    {
        return !$this->needsVueFrontend() && $this->isEditOrShow();
    }

    /**
     * Determine if the resource is of the edit or show type.
     *
     * @return boolean
     */
    protected function isEditOrShow() : bool
    {
        return in_array($this->type, [
            'edit',
            'show',
        ]);
    }

    /**
     * Get the input value.
     *
     * @return string
     */
    protected function getInputValue() : string
    {
        return '{{ $' . $this->getCamelCaseSingular($this->nameOfResource) . '->' . $this->column['name'] . ' }}';
    }
}