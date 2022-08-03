<?php

namespace Cruddy\Stubs;

use Cruddy\Stubs\WithoutPlaceholders\StubWithoutPlaceholders;
use Illuminate\Support\Facades\App;

abstract class CollectionStub extends StubWithoutPlaceholders
{
    /**
     * The acceptable stubs.
     *
     * @var string[]
     */
    protected $acceptableStubs = [];

    /**
     * The parameters for the stub.
     *
     * @var mixed[]
     */
    protected $parameters = [];

    /**
     * Set the stub parameters.
     *
     * @param  string  $stubClass
     * @return self
     */
    abstract protected function setStubParameters(string $stubClass) : self;

    /**
     * The constructor method.
     *
     * @param  array  $stubs = []
     * @return void
     */
    public function __construct($stubs = [])
    {
        parent::__construct();

        $this->setInitialStub()
            ->removeExtraSpacer();
    }

    /**
     * Determine if stub should be empty.
     *
     * @return boolean
     */
    protected function shouldHaveStub() : bool
    {
        return true;
    }

    /**
     * Get the acceptable stubs within the provided stubs.
     *
     * @return array
     */
    protected function getStubs() : array
    {
        if (count($this->stubs) === 0) {
            return $this->acceptableStubs;
        }

        return array_filter($this->stubs, function ($stub) {
            return in_array($stub, $this->acceptableStubs);
        });
    }

    /**
     * Set the initial stub.
     *
     * @return self
     */
    protected function setInitialStub() : self
    {
        $stubs = $this->getStubs();

        foreach ($stubs as $stub) {
            $this->setStubParameters($stub);
            $this->addStub($stub);
        }

        return $this;
    }

    /**
     * Add a stub.
     *
     * @param  string  $stub
     * @return void
     */
    protected function addStub(string $stub) : void
    {
        $this->addValue($this->makeUpdatedStub($stub));
    }

    /**
     * Make and get an updated stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function makeUpdatedStub(string $stub) : string
    {
        return App::make($stub, $this->parameters)
            ->getUpdatedStub();
    }

    /**
     * Get the initial stub.
     *
     * @return string
     */
    protected function getInitialStub() : string
    {
        return $this->stub;
    }

    /**
     * If there is an extra spacer at the end of the stub, then remove it.
     *
     * @return self
     */
    protected function removeExtraSpacer() : self
    {
        if ($this->endsInSpacer()) {
            $this->stub = substr($this->stub, 0, -strlen($this->spacer));
        }

        return $this;
    }

    /**
     * Determine if the stub ends in a spacer.
     *
     * @return boolean
     */
    protected function endsInSpacer() : bool
    {
        return substr($this->stub, -strlen($this->spacer)) === $this->spacer;
    }
}