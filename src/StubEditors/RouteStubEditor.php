<?php

namespace Cruddy\StubEditors;

use Illuminate\Support\Facades\File;

class RouteStubEditor extends StubEditor
{
    /**
     * The resource route.
     *
     * @var string
     */
    protected $resourceRoute = '';

    /**
     * The default resource type.
     *
     * @var string
     */
    protected $defaultResourceType = 'resource';

    /**
     * The default route file.
     * 
     * @var string
     */
    protected $defaultRouteFileName = 'web';

    /**
     * The success message.
     *
     * @var string
     */
    protected $successMessage = "Cruddy resource routes were added successfully!\n";

    /**
     * The no routes were added message.
     *
     * @var string
     */
    protected $noRoutesAddedMessage = "No Cruddy resource routes were added.\n";

    /**
     * The stub file location.
     *
     * @var string
     */
    protected $stubLocation = '';

    /**
     * The constructor method.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setStubLocation()
            ->setResourceRoute()
            ->setStub();
    }

    /**
     * Get the type of route resource needed.
     *
     * @return string
     */
    protected function getResourceType() : string
    {
        return $this->getApi() ? 'apiResource' : $this->defaultResourceType;
    }

    /**
     * Get the route file type.
     *
     * @return string
     */
    protected function getRouteFileName() : string
    {
        return $this->getApi() ? 'api' : $this->defaultRouteFileName;
    }

    /**
     * Set the stub location.
     *
     * @return self
     */
    protected function setStubLocation() : self
    {
        $this->stubLocation = 'routes/' . $this->getRouteFileName() .  '.php';

        return $this;
    }

    /**
     * Set the stub.
     *
     * @return self
     */
    protected function setStub() : self
    {
        $this->stub = $this->getStubFile();

        return $this;
    }

    /**
     * Get the stub.
     *
     * @return string
     */
    public function getStubFile() : string
    {
        return File::get($this->stubLocation);
    }

    /**
     * Set the resource route.
     *
     * @return self
     */
    protected function setResourceRoute() : self
    {
        $this->resourceRoute = $this->getResourceRoute();
    
        return $this;
    }

    /**
     * Get resource route.
     *
     * @return string
     */
    protected function getResourceRoute() : string
    {
        $name = $this->getResourceName();
        $lowerPluralName = $this->getLowerPlural($name);
        $ucFirstName = ucfirst($name);

        return "\n\n// $ucFirstName Resource\n" . 
            "Route::" . $this->getResourceType() . "('$lowerPluralName', 'App\Http\Controllers\\" . $ucFirstName . "Controller');";
    }

    /**
     * Determine if the stub file already has the resource route.
     *
     * @return boolean
     */
    protected function hasResourceRoute() : bool
    {
        return strpos($this->stub, $this->resourceRoute) !== false;
    }

    /**
     * Add the resource route to the stub.
     *
     * @return string
     */
    public function addResourceRoute() : string
    {
        if ($this->hasResourceRoute()) {
            return $this->noRoutesAddedMessage;
        }

        File::append($this->stubLocation, $this->resourceRoute);

        return $this->successMessage;

    }
}