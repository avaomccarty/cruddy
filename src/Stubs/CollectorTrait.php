<?php

namespace Cruddy\Stubs;

use Illuminate\Support\Facades\App;

trait CollectorTrait
{
    // /**
    //  * The collection stub class.
    //  *
    //  * @var string
    //  */
    // protected $collectionStubClass;

    /**
     * Get the collection stub parameters.
     *
     * @return array
     */
    abstract protected function getParameters() : array;

    /**
     * Get the validation for the default relationship.
     *
     * @return string
     */
    protected function getInitialStub() : string
    {
        return App::make($this->collectionStubClass, $this->getParameters())
            ->getUpdatedStub();
    }

    /**
     * Set the initial stub.
     *
     * @return self
     */
    protected function setInitialStub() : self
    {
        $this->initialStub = $this->getInitialStub();

        return $this;
    }

    /**
     * Determine if stub should be empty.
     *
     * @return boolean
     */
    protected function shouldHaveStub() : bool
    {
        // Note: should this actually be false or depend on a property so it can be overwritten on a case-by-case basis??
        return $this->shouldHaveStub ?? true;
    }
}