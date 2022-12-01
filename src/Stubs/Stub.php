<?php

namespace Cruddy\StubEditors;

use Cruddy\Traits\CommandTrait;

abstract class Stub
{
    use CommandTrait;

    protected string $stub;
    protected string $initialStub = '';
    protected string $stubFileType = '';
    protected string $spacer = '';

    abstract public function getStub() : string;

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
     * Get the stub.
     *
     * @return self
     */
    protected function setStub() : self
    {
        $this->addToStub($this->getInitialStub())
            ->addSpacer();

        return $this;
    }

    /**
     * Add value to the stub.
     *
     * @param  string  $value
     * @return self
     */
    protected function addToStub(string $value) : self
    {
        $this->stub .= $value;

        return $this;
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
     * Add a spacer within the stub.
     *
     * @return void
     */
    protected function addSpacer() : void
    {
        $this->addToStub($this->getSpacer());
    }

    /**
     * Get the spacer for the stub.
     *
     * @return string
     */
    protected function getSpacer() : string
    {
        return $this->spacer;
    }

    /**
     * Get the stub file type.
     *
     * @return string
     */
    protected function getStubFileType() : string
    {
        return $this->stubFileType;
    }
}