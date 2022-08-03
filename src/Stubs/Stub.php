<?php

namespace Cruddy\Stubs;

use Cruddy\Traits\CommandTrait;

abstract class Stub
{
    use CommandTrait;

    /**
     * The initial stub.
     *
     * @var string
     */
    protected $initialStub = '';
    
    /**
     * The stub.
     *
     * @var string
     */
    protected $stub = '';

    /**
     * The string needed between values.
     *
     * @var string
     */
    protected $spacer = '';

    /**
     * Get the updated stub.
     *
     * @return string
     */
    abstract public function getStub() : string;

    /**
     * Get the initial stub.
     *
     * @return string
     */
    abstract protected function getInitialStub() : string;

    /**
     * Determine if stub should be empty.
     *
     * @return boolean
     */
    abstract protected function shouldHaveStub() : bool;

    /**
     * Get the stub.
     *
     * @return self
     */
    protected function setStub() : self
    {
        $value = $this->shouldHaveStub() ? $this->getInitialStub() : '';
        $this->addValue($value);

        return $this;
    }

    /**
     * The constructor method.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setStub();
    }

    /**
     * Add value to the stub.
     *
     * @param  string  $value
     * @return void
     */
    protected function addValue(string $value) : void
    {
        if (!empty($value)) {
            $this->stub .= $value;
            $this->addSpacer();
        }
    }

    /**
     * Add a spacer within the stub.
     *
     * @return void
     */
    protected function addSpacer() : void
    {
        $this->stub .= $this->spacer;
    }

    /**
     * Get the stub file type.
     *
     * @return string
     */
    protected function getStubFileType() : string
    {
        // Todo: Do we need this method anymore??
        return $this->stubFileType;
    }

    /**
     * Check if the stub already contains the provided string.
     *
     * @param  string  $needle
     * @return boolean
     */
    public function stubContains(string $needle) : bool
    {
        return str_contains($this->stub, $needle);
    }

    /**
     * Get the input string for the controller.
     *
     * @return string
     */
    protected function getStubEditorInputStrings() : string
    {
        return $this->getInputsStubEditor($this->stubEditorType)
            ->getInputStrings();
    }
}