<?php

namespace Cruddy\Traits;

use Illuminate\Support\Str;

trait RouteAddCommandTrait
{
    use CommandTrait;

    /**
     * The name of the resource.
     *
     * @var string
     */
    protected $name;

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
     * Get resource route.
     *
     * @return string
     */
    protected function getResourceRoute() : string
    {
        $name = $this->getResourceName();
        $lowerName = $this->getLowerPlural($name);
        $ucFirstName = Str::ucfirst($name);
        $routeString = "\n\n" .
                    "// $ucFirstName Resource\n" .
                    "Route::" . $this->getResourceType() . "('$lowerName', 'App\Http\Controllers\\" . $ucFirstName . "Controller')";

        return $routeString . ';';
    }

    /**
     * Get the resource name.
     *
     * @return string
     */
    protected function getResourceName() : string
    {
        if (method_exists(self::class, 'argument')) {
            return $this->argument('name') ?? '';
        }

        return $this->name ?? '';
    }

    /**
     * Get the type of route resource needed.
     *
     * @return string
     */
    protected function getResourceType() : string
    {
        if (method_exists(self::class, 'option')) {
            return $this->option('api') ? 'apiResource' : $this->defaultResourceType;
        }

        return $this->defaultResourceType;
    }

    /**
     * Get the route file type.
     *
     * @return string
     */
    protected function getRouteFileName() : string
    {
        if (method_exists(self::class, 'option')) {
            return $this->option('api') ? 'api' : $this->defaultRouteFileName;
        }

        return $this->defaultRouteFileName;
    }

    /**
     * Get the route file.
     *
     * @return string
     */
    protected function getRouteFile() : string
    {
        return 'routes/' . $this->getRouteFileName() .  '.php';
    }
}