<?php

namespace Cruddy\StubEditors;

use Illuminate\Support\Facades\File;

class ControllerStubEditor extends StubEditor
{
    /**
     * Determines if the controller is for an API.
     *
     * @var boolean
     */
    protected $isApi = false;

    /**
     * Set the value to determine if this is for an API controller.
     *
     * @param  boolean  $isApi = false
     * @return void
     */
    public function setIsApi(bool $isApi = false) : void
    {
        $this->isApi = $isApi;
    }

    /**
     * Get the stub.
     *
     * @return string
     */
    public function getStubFile() : string
    {
        if ($this->isApi) {
            return File::get($this->resolveStubPath($this->getStubsLocation() . '/controller.api.stub'));
        }

        return File::get($this->resolveStubPath($this->getStubsLocation() . '/controller.stub'));
    }
}