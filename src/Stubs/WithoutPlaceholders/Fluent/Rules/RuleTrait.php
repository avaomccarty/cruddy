<?php

namespace Cruddy\Stubs\WithoutPlaceholders\Fluent\Rules;

use Cruddy\StubEditors\EmptyPlaceholderStubParametersTrait;

trait RuleTrait
{
    use EmptyPlaceholderStubParametersTrait;

    /**
     * The string needed between values.
     *
     * @var string
     */
    protected $spacer = '|';

    /**
     * Determine if the column if for an integer.
     *
     * @return bool
     */
    protected function isIntegerColumn() : bool
    {
        return isset($this->column->type) && ($this->column->type === 'integer' || $this->column->type === 'bigInteger');
    }

    /**
     * Get the initial stub.
     *
     * @return string
     */
    protected function getInitialStub() : string
    {
        return $this->initialStub;
    }

    /**
     * Set the initial stub.
     *
     * @return self
     */
    protected function setInitialStub() : self
    {
        return $this;
    }
}