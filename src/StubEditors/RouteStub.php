<?php

namespace Cruddy\StubEditors;

use Illuminate\Support\Facades\File;

class RouteStub extends HasPlaceholderStub
{
    /**
     * The stub file type.
     *
     * @var string
     */
    protected $stubFileType = 'route';

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
        parent::__construct();

        $this->setResourceRoute();
    }

    /**
     * Set the placeholder value map.
     *
     * @return self
     */
    protected function setPlaceholderValueMap() : self
    {
        $this->placeholderValueMap = [];

        return $this;
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
     * @return void
     */
    protected function addResourceRoute() : void
    {
        File::append($this->getStubLocation(), $this->resourceRoute);
    }

    /**
     * Get the stub file location.
     *
     * @return string
     */
    protected function getStubLocation() : string
    {
        return 'routes/' . $this->getRouteFileName() .  '.php';
    }

    /**
     * Update the stub file.
     *
     * @return self
     */
    protected function updateStub() : self
    {
        $this->addResourceRoute();

        return $this;
    }
}